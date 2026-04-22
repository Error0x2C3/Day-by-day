<?php

require_once 'model/Member.php';
require_once 'model/Message.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMember extends Controller {
    
    const UPLOAD_ERR_OK = 0;

    //gestion de l'édition du profil
    public function edit_profile() : void {
        $member = $this->get_user_or_redirect();
        $errors = [];
        $success = "";

        // Il est nécessaire de vérifier le statut de l'erreur car, dans le cas où on fait un submit
        // sans avoir choisi une image, $_FILES['image'] est "set", mais le statut 'error' est à 4 (UPLOAD_ERR_NO_FILE).
        if (isset($_FILES['image']) && $_FILES['image']['error'] === self::UPLOAD_ERR_OK) {
            $errors = Member::validate_photo($_FILES['image']);
            if (empty($errors)) {
                $saveTo = $member->generate_photo_name($_FILES['image']);
                $oldFileName = $member->picture_path;
                if ($oldFileName && file_exists("upload/" . $oldFileName)) {
                    unlink("upload/" . $oldFileName);
                }
                move_uploaded_file($_FILES['image']['tmp_name'], "upload/$saveTo");
                $member->picture_path = $saveTo;
                $member->persist();
            } 
        }

        if (isset($_POST['profile'])) {
            //le profil peut être vide : pas de soucis.
            $profile = $_POST['profile'];
            $member->profile = $profile;
            $member->persist();
        }

        // si on est en POST et sans erreurs, on redirige avec un paramètre 'ok'
        if (count($_POST) > 0 && count($errors) == 0)
            $this->redirect("member", "edit_profile", "ok");

        // si param 'ok' dans l'url, on affiche le message de succès
        if (isset($_GET['param1']) && $_GET['param1'] === "ok")
            $success = "Your profile has been successfully updated.";

        (new View("edit_profile"))->show(["member" => $member, "errors" => $errors, "success" => $success]);
    }



    //page d'accueil. 
    public function index() : void {
        $this->profile();
    }

    //profil de l'utilisateur connecté ou donné
    public function profile() : void {
        $member = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $member = Member::get_member_by_pseudo($_GET["param1"]);
        }
        (new View("profile"))->show(["member" => $member]);
    }

    //liste des membres.
    public function members() : void {
        $member = $this->get_user_or_redirect();
        $members = $member->get_other_members_and_relationships();
        (new View("members"))->show(["member" => $member, "members" => $members]);
    }

    //gestion du suivi d'un membre
    public function follow() : void {
        $member = $this->get_user_or_redirect();
        if (isset($_POST["follow"]) && $_POST["follow"] != "") {
            $followee_pseudo = $_POST["follow"];
            $followee = Member::get_member_by_pseudo($followee_pseudo);
            if ($followee === false)
                throw new Exception("Unknown member");
            $member->follow($followee);
            $this->redirect("member", "members");
        } else {
            throw new Exception("Missing ID");
        }
    }

    //gestion de la suppression du suivi d'un membre
    public function unfollow() : void {
        $member = $this->get_user_or_redirect();
        if (isset($_POST["unfollow"]) && $_POST["unfollow"] != "") {
            $followee_pseudo = $_POST["unfollow"];
            $followee = Member::get_member_by_pseudo($followee_pseudo);
            if ($followee === false)
                throw new Exception("Unknown member");
            $member->unfollow($followee);
            $this->redirect("member", "members");
        } else {
            throw new Exception("Missing ID");
        }
    }
    
        //gestion des amis d'un utilisateur
    public function friends() : void {
        $user = $this->get_user_or_redirect();
        $members = $user->get_other_members_and_relationships();

        $mutuals = [];
        $followers = [];
        $followees = [];

        foreach ($members as $member) {
            if ($member["followee"] == 1 && $member["follower"] == 1) {
                $mutuals[] = $member["pseudo"];
            } else if ($member["followee"] == 1) {
                $followers[] = $member["pseudo"];
            } else if ($member["follower"] == 1) {
                $followees[] = $member["pseudo"];
            }
        }
        (new View("friends"))->show(["user" => $user, "mutuals" => $mutuals, "followers" => $followers, "followees" => $followees]);
    }

}
