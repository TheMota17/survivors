<?php

    require '../Utils.php';

    require '../SessStart.php';

    Class AuthTokens {
        public function sendTokens()
        {
            Messenger::sendMessage(['authCode' => $this->getTokens()], false, false);
        }

    	public function getTokens()
        {
            return $_SESSION['authCode'] = GenRandCodes::authCode();
    	}
    }

    $AuthTokens = new AuthTokens;
    $AuthTokens->sendTokens();