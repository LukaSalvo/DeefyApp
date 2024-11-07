<?php

namespace iutnc\deefy\action;


use iutnc\deefy\auth\AuthnProvider;

class Logout extends Action
{


    public function execute(): string{

        $authn = new AuthnProvider();

        $authn->logoutUser();

        header('Location: ?action=default');
        exit();
    }


}