<?php
/*
* This page displays the raid boss page.
*/
$access = "user";                   // Define access level
include 'includes/before.php';      // Get initial boilerplate
include 'includes/combat.php';      // For combat stuffs!

# Get the boss data
# Get available pet data for current user
# Get 
# Send em all to the view

// Get boss data
$sql = "
    SELECT
    b.id,
    p.name as name,
    p.bio,
    u.name as owner,
    s.img,
    s.name as species,
    t.name as type,
    b.maxhp,
    b.hp,
    b.att,
    b.def,
    b.reward,
    b.bonus
    FROM boss b
    JOIN pets p ON b.pet = p.id
    JOIN users u ON p.owner = u.id
    JOIN species s ON p.species = s.id
    JOIN types t ON s.type = t.id
    WHERE b.active = TRUE";
$bosses = $db->query($sql);

// Build boss data
$bossFlag = true;
if ($boss = $bosses->fetch_assoc()) {
    $b_array = array(
        "id" => $boss['id'],
        "name" => $boss['name'],
        "bio" => $boss['bio'],
        "owner" => $boss['owner'],
        "img" => $boss['img'],
        "species" => $boss['species'],
        "type" => $boss['type'],
        "maxhp" => $boss['maxhp'],
        "hp" => $boss['hp'],
        "att" => $boss['att'],
        "def" => $boss['def'],
        "reward" => $boss['reward'],
        "bonus" => $boss['bonus']
    );
} else {
    $bossFlag = false;
}

// Get available pet data
$sql = "
    SELECT
    p.id,
    p.name,
    s.img,
    p.maxhp,
    p.hp,
    p.att,
    p.def,
    r.name as rarity
    FROM pets p
    JOIN species s ON p.species = s.id
    JOIN rarity r ON s.rarity = r.id
    WHERE p.owner = {$_SESSION['id']}
    AND p.busy = FALSE
    AND p.alive = TRUE";
$pets = $db->query($sql);

// Build pet data
$p_array = array();
while ($pet = $pets->fetch_assoc()) {
    $p = array(
        "id" => $pet['id'],
        "name" => $pet['name'],
        "img" => $pet['img'],
        "maxhp" => $pet['maxhp'],
        "hp" => $pet['hp'],
        "att" => $pet['att'],
        "def" => $pet['def'],
        "ed" => estimateBossDamage($pet['att'], $b_array['def']),
        "et" => estimateDamage($b_array['att'], $pet['def']),
        "rarity" => $pet['rarity']
    );
    array_push($p_array, $p);
}

// Get boss damage chart data (only if there is a boss tho!)
if ($bossFlag) {
    $sql = "
        SELECT
        u.name as owner,
        p.name as pet,
        b.dmg
        FROM boss_dmg b
        JOIN users u ON b.owner = u.id
        JOIN pets p ON b.pet = p.id 
        WHERE b.boss = {$b_array['id']}";
    $damage = $db->query($sql);

    // Build damage array
    $d_array = array();
    while ($dmg = $damage->fetch_assoc()) {
        $d = array(
            "owner" => $dmg['owner'],
            "pet" => $dmg['pet'],
            "dmg" => $dmg['dmg']
        );
        array_push($d_array, $d);
    }
    $d_array = json_encode($d_array);   // A JS library will need this in JSON
}

// Attach data to views
$smarty->assign("pets", $p_array);
if ($bossFlag) {                        // Only send boss data if boss exists
    $smarty->assign("boss", $b_array);
    $smarty->assign("dmg", $d_array);
} else {
    $smarty->assign("boss", false);
    $smarty->assign("dmg", false);
}

// Finish page processing
include 'includes/after.php';
$dir = dirname(__FILE__);
$smarty->display("$dir/views/boss.tpl");
