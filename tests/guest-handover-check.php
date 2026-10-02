<?php

/**
 * A guest's items must become the account's items on sign-in, once, and stop
 * being reachable through the guest cookie.
 *
 * transferSessionToUser() only set user_id and left session_id in place. The
 * guest cookie outlives the login (six months, untouched by logout), and every
 * guest lookup matched on session_id alone, so after logout the same browser
 * listed the account's items, counted them, and a click on "Remove" deleted
 * the account's row. A product saved on both sides was listed twice.
 *
 * Needs WordPress and the plugin active, so it runs inside wp-env:
 *   wp eval-file wp-content/plugins/plogins-shortlist-tests/guest-handover-check.php
 * (or any path the container can read). It writes to a scratch user id and
 * removes its rows afterwards.
 */


defined('ABSPATH') || exit(1);

$repo    = new \Shortlist\Repository\WishlistTableRepository();
$user    = 990001;
$session = 'handover-check-' . wp_generate_uuid4();
$failed  = [];

global $wpdb;
$table   = $repo->table();
$cleanup = static function () use ($wpdb, $table, $user, $session): void {
    $wpdb->query($wpdb->prepare('DELETE FROM %i WHERE user_id = %d OR session_id = %s', $table, $user, $session));
};
$cleanup();

$ids = get_posts(['post_type' => 'product', 'post_status' => 'publish', 'numberposts' => 3, 'fields' => 'ids', 'orderby' => 'ID', 'order' => 'ASC']);
if (count($ids) < 3) {
    echo "SKIP: needs three published products\n";
    return;
}
[$a, $b, $c] = array_map('intval', $ids);

$check = static function (string $label, bool $ok) use (&$failed): void {
    echo ($ok ? '  ok      ' : '  FAILED  ') . $label . "\n";
    if (! $ok) {
        $failed[] = $label;
    }
};

// The account already holds A; the guest saved A and B.
$repo->add($a, $user, null);
$repo->add($a, null, $session);
$repo->add($b, null, $session);

$repo->transferSessionToUser($session, $user);

$list = $repo->findProductIds($user, null);
sort($list);
$want = [$a, $b];
sort($want);
$check('account holds A and B once each after sign-in', $list === $want);
$check('account count is 2', $repo->countProductIds($user, null) === 2);
$check('the guest cookie lists nothing after logout', $repo->findProductIds(null, $session) === []);
$check('the guest cookie counts nothing after logout', $repo->countProductIds(null, $session) === 0);
$check('the guest cookie does not see A as saved', ! $repo->exists($a, null, $session));

$repo->remove($a, null, $session);
$check('a guest remove does not delete the account row', $repo->exists($a, $user, null));

// Rows transferred by 1.0.20 and earlier kept their session id. They must stay
// invisible to the cookie as well.
$wpdb->insert($table, ['product_id' => $c, 'user_id' => $user, 'session_id' => $session, 'created_at' => current_time('mysql', true)], ['%d', '%d', '%s', '%s']);
$check('a legacy transferred row is not listed for the cookie', $repo->findProductIds(null, $session) === []);
$repo->remove($c, null, $session);
$check('a legacy transferred row survives a guest remove', $repo->exists($c, $user, null));

$cleanup();

echo "\n" . ($failed === [] ? "RESULT: pass\n" : 'RESULT: ' . count($failed) . " failed\n");
if ($failed !== []) {
    exit(1);
}
