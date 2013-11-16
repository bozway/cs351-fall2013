<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20130720002202 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
	    $user = new \Entity\User;
	    $user->setFirstName("hao");
	    $user->setLastName("cai");
	    $user->setPassword("123456");
	    $user->setEmail("wr@writ.com");
	    $user->setCreationTime(new \DateTime());
		$user->setRegistrationIP("rip");
		$user->setSignupStage("2");
        $user->setLastLoginIP("lip");
		
		//$user->save();
        

    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
