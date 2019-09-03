<?php

use frontend\models\SignupForm;
use yii\db\Migration;

/**
 * Class m190902_090536_fill_users_table
 */
class m190902_090536_fill_users_table extends Migration
{
    /**
     * @return bool|void
     * @throws \yii\base\Exception
     */
    public function safeUp()
    {
        $users = [
            [
                'username' => 'Tester',
                'email' => 'tester@test.com',
                'password' => 'test12344',
            ]
        ];

        foreach ($users as $user) {
            $form = new SignupForm();

            $form->load($user, '');

            $form->signup(false, true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%user}}');
    }
}
