<?php
class AccountSelect extends MultiAccountLoginModel {
    /**
     * FetchByUserID()
     * @params: $uid - The user ID to get details of.
     *
     * @return: array():
     *  [0] - Email of user ID
     *  [1] - Companies that email is attached to
     **/
    public function FetchByUserID($uid){
        Loader::loadComponents($this, array("Record", "Session"));
        
        $email = $this->Record->select(array("contacts.email" => "email"))->from("contacts")->
            leftJoin("clients", "clients.id", "=", "contacts.client_id", false)->
            where("clients.user_id", "=", $uid)->fetch()->email;
        
        /**
         * select contacts.client_id,contacts.company,clients.id_value from contacts
         *  left join clients on contacts.client_id=clients.id left join client_groups on c
         *  lient_groups.id=clients.client_group_id where clients.status='active' and client
         *  _groups.company_id=2 and contacts.email='test@example.com';
         **/
        $uids = $this->Record->select(
            array("contacts.client_id", "contacts.company", "clients.id_value", "clients.user_id")
        )->from("contacts")->leftJoin("clients", "clients.id", "=", "contacts.client_id", false)->
        leftJoin("client_groups", "client_groups.id","=","clients.client_group_id", false)->
        where("clients.status", "=", "active")->where("client_groups.company_id", "=", Configure::get("Blesta.company_id"))->
        where("contacts.email", "=", $email)->fetchAll();
        
        return array($email, $uids);
    }
    
    public function is_admin($uid){
        Loader::loadComponents($this, array("Record"));
        
        if($this->Record->select("id")->from("staff")->where("user_id", "=", $uid)->where("status", "=", "active")->numResults())
            return true;
        
        return false;
    }
}

?>