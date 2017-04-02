<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use common\fixtures\User as UserFixture;

class HomeCest
{
    function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
        $I->amLoggedInAs(1);
    }

    public function checkOpen(FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->see('Analytic', 'h1');
        $I->seeLink('Analytic', '/');
        $I->seeLink('Wallet', '/wallets');
        $I->seeLink('Category', '/categories');
        $I->seeLink('Income', '/incomes');
        $I->seeLink('Outcome', '/outcomes');
        $I->seeLink('Settings', '/settings');
        $I->canSee('Exit', '.logout-btn');
        $I->click('Wallet');
        $I->see('Wallet', 'h1');
    }
}