<?php

require_once 'model/Member.php';
require_once 'model/Message.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerMessage extends Controller {

    //accueil du controlleur.
    //gère l'affichage des messages et le post
    public function index() : void {
        $user = $this->get_user_or_redirect();
        $recipient = $this->get_recipient($user);
        $errors = [];

        if (isset($_POST['body'])) {
            $errors = $this->post($user, $recipient);
        }

        $messages = $recipient->get_messages();
        $messages_json = $recipient->get_visible_messages_as_json($user);
        (new View("messages"))->show(["recipient" => $recipient, 
                                      "user" => $user, 
                                      "messages" => $messages, 
                                      "messages_json" => $messages_json,
                                      "errors" => $errors]);
    }

    public function get_messages_jason_trie(String $message_jason, String $trie){

    }
    //méthode outil pour poster un  message. renvoie un tableau d'erreurs 
    //eventuellement vide
    private function post(Member $user, Member $recipient) : array {
        $errors = [];
        if (isset($_POST['body'])) {
            $body = $_POST['body'];
            $private = isset($_POST['private']) ? TRUE : FALSE;
            $message = new Message($user, $recipient, $body, $private);
            $errors = $message->validate();
            if(empty($errors)){
                $user->write_message($message);        
                $this->redirect("message", "index", $recipient->pseudo);        
            }
        }
        return $errors;
        
    }

    //méthode outil pour déterminer le destinataire courant
    private function get_recipient(Member $user) : Member {
        if (!isset($_GET["param1"]) || $_GET["param1"] == "") {
            return $user;
        } else {
            return Member::get_member_by_pseudo($_GET["param1"]);
        }
    }

    //gère la suppression d'un message
    public function delete() : void {
        $message = $this->remove_message();
        if ($message) {
            $member = $message->recipient;
            $this->redirect("message", "index", $member->pseudo);
        } else {
            throw new Exception("Wrong/missing ID or action no permited");
        }
    }

    //méthode outil pour supprimer un message.
    //indique si le message a été effectivement supprimé (dans ce cas renvoie le message)
    //sinon renvoie false
    private function remove_message() : Message|false {
        $user = $this->get_user_or_redirect();

        if (isset($_POST['id_message']) && $_POST['id_message'] != "") {
            $post_id = $_POST['id_message'];
            $message = Message::get_message($post_id);
            if ($message) {
                return $message->delete($user);
            } 
        }
        return false;
    }

}
