<?php
    /*
    * This page displays ta user profile page.
    */
    $access = "user";                   // Define access level
    include 'includes/before.php';      // Get initial boilerplate

    /*
    *   So basically, what we wanna do is get either:
    *       - the current user if not $_GET['id'] is assigned
    *       - the supplied user if a $_GET['id'] is assigned
    *   Then we're gonna grab a bunch of that users data and send it to the view
    */
    $edit = "";
    if (isset($_GET['id'])) {           // If we were supplied with an ID
        $id = $_GET['id'];              // Use that one
        if (isset($_SESSION['id']) && $_GET['id'] == $_SESSION['id'])
            $edit = '<button type="button" class="btn btn-primary btn-lg btn-block" id="edit">Edit</button>';
    } else {                            // If we WERENT
        $id = $_SESSION['id'];          // Use the current user's ID
        $edit = '<button type="button" class="btn btn-primary btn-lg btn-block" id="edit">Edit</button>';
    }
    
    /*************************************   Grab question data   *************************************/
    $questions = $db->query("SELECT code, date FROM questions WHERE user = {$id} AND verified = 1 ORDER BY date DESC");   
    if ($questions === false) {throw new Exception ($db->error);}               // If something went wrong
    $q_array = array();                                                         // Get ready for row data
    while ($row = $questions->fetch_assoc()) {                                  // Iterate over each question
        $q = array();
        $q['date'] = $row['date'];
        $q['link'] = "<a href='http://strawpoll.me/{$row['code']}' target='_blank'>{$row['code']}</a>";
        array_push($q_array, $q);                                               // And store the data into a result row
    }

    $db->next_result();

    /***************************************   Grab user data   ***************************************/
    $sql = "SELECT u.name as name, u.summoner_id, u.steam_id, u.avatar, r.name as role, u.scum_points, u.twitter, u.website, u.intro, u.about
            FROM users u JOIN roles r WHERE r.id = u.role AND u.id = ?";
    if (! $sth = $db->prepare($sql)){throw new Exception ("SQL ($sql) failed: ". $db->error);}
    if (! $sth->bind_param("i",$id)) {throw new Exception ("Bind Param failed: ".__LINE__);}
    if (! $sth->bind_result($name, $l_id, $s_id, $avatar, $role, $scumPoints, $twitter, $website, $intro, $about)){throw new Exception ("Bind Result failed: ".__LINE__);}

    // Get a user from database
    if (!$result = $sth->execute()){throw new Exception ("Execute failed: ".$db->error);}

    // Get results (only need to get one row, because users are unique)
    $sth->fetch();
    $sth->close();
    $db->next_result();



    /*************************************** Get users pet data *****************************************/
    $pets = $db->query("
            SELECT
            p.id,
            p.name,
            p.bio,
            p.hp,
            p.maxhp,
            p.att,
            p.def,
            p.hunger,
            p.maxhunger,
            s.img,
            s.name as species,
            s.flavour,
            t.name as type,
            r.name as rarity,
            p.defending
            FROM pets p
            JOIN users u ON p.owner = u.id
            JOIN species s ON p.species = s.id
            JOIN rarity r ON s.rarity = r.id
            JOIN types t ON s.type = t.id
            WHERE p.owner = {$id}
            AND p.alive = true
            AND p.busy = false"
        );   
        if ($pets === false) {throw new Exception ($db->error);}                // If something went wrong
        $p_array = array();                       

        while ($row = $pets->fetch_assoc()) {                                   // Iterate over each pet
            $p = array();
            $p['id'] = $row['id'];
            $p['name'] = $row['name'];
            $p['hp'] = $row['hp'];
            $p['maxhp'] = $row['maxhp'];
            $p['att'] = $row['att'];
            $p['def'] = $row['def'];
            $p['hunger'] = $row['hunger'];
            $p['maxhunger'] = $row['maxhunger'];
            $p['img'] = $row['img'];
            $p['species'] = $row['species'];
            $p['type'] = $row['type'];
            $p['text'] = $row['bio'];
            $p['rarity'] = $row['rarity'];
            $p['defending'] = $row['defending'];
            if (is_null($p['text']) || $p['text'] == "")
                $p['text'] = $row['flavour'];
            array_push($p_array, $p);
        }

    include 'includes/after.php';

    // Pass all the user data shit to the view
    $qry = "?user={$id}";
    if ($id == $_SESSION['id'])
        $qry = "";

    $smarty->assign('qry', $qry);
    $smarty->assign('name', $name);
    $smarty->assign('l_id', $l_id);
    $smarty->assign('s_id', $s_id);
    $smarty->assign('avatar', $avatar);
    $smarty->assign('role', $role);
    $smarty->assign('scumPoints', $scumPoints);
    $smarty->assign('twitter', $twitter);
    $smarty->assign('website', $website);
    $smarty->assign('intro', $intro);
    $smarty->assign('about', $about);
    $smarty->assign('edit', $edit);
    $smarty->assign('questions', $q_array);
    $smarty->assign('pets', $p_array);

    // Display the associated template
    $dir = dirname(__FILE__);
    $smarty->display("$dir/views/user.tpl");
?>