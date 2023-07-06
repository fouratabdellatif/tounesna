<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230403155704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activities CHANGE gouvernorat gouvernorat INT DEFAULT NULL, CHANGE auteur auteur INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity_likes CHANGE activity_id activity_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE articleculturel CHANGE id_gouv id_gouv INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire_likes DROP FOREIGN KEY commentaire_likes_ibfk_1');
        $this->addSql('ALTER TABLE commentaire_likes DROP FOREIGN KEY commentaire_likes_ibfk_2');
        $this->addSql('ALTER TABLE commentaire_likes CHANGE com_id com_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire_likes ADD CONSTRAINT FK_CC468661748C0F37 FOREIGN KEY (com_id) REFERENCES commentaire (idcom)');
        $this->addSql('ALTER TABLE commentaire_likes ADD CONSTRAINT FK_CC468661A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (idUser)');
        $this->addSql('ALTER TABLE commentaire_reported DROP FOREIGN KEY commentaire_reported_ibfk_1');
        $this->addSql('ALTER TABLE commentaire_reported DROP FOREIGN KEY commentaire_reported_ibfk_2');
        $this->addSql('ALTER TABLE commentaire_reported CHANGE id_com id_com INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commentaire_reported ADD CONSTRAINT FK_70139D7FFA122EBC FOREIGN KEY (id_com) REFERENCES commentaire (idcom)');
        $this->addSql('ALTER TABLE commentaire_reported ADD CONSTRAINT FK_70139D7F6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (idUser)');
        $this->addSql('ALTER TABLE evenement CHANGE gouvernorat gouvernorat INT DEFAULT NULL, CHANGE auteur auteur INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel CHANGE gouvernorat gouvernorat INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD price INT NOT NULL, CHANGE gouvernorat gouvernorat INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC274457C12B FOREIGN KEY (gouvernorat) REFERENCES gouvernorat (id_gouver)');
        $this->addSql('CREATE INDEX IDX_29A5EC274457C12B ON produit (gouvernorat)');
        $this->addSql('ALTER TABLE rate_evenement DROP FOREIGN KEY rate_evenement_ibfk_1');
        $this->addSql('ALTER TABLE rate_evenement CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rate_evenement ADD CONSTRAINT FK_AA202C706B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (idUser)');
        $this->addSql('ALTER TABLE rate_hotel CHANGE id_hotel id_hotel INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activities CHANGE auteur auteur INT NOT NULL, CHANGE gouvernorat gouvernorat INT NOT NULL');
        $this->addSql('ALTER TABLE activity_likes CHANGE user_id user_id INT NOT NULL, CHANGE activity_id activity_id INT NOT NULL');
        $this->addSql('ALTER TABLE articleculturel CHANGE id_gouv id_gouv INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire_likes DROP FOREIGN KEY FK_CC468661748C0F37');
        $this->addSql('ALTER TABLE commentaire_likes DROP FOREIGN KEY FK_CC468661A76ED395');
        $this->addSql('ALTER TABLE commentaire_likes CHANGE com_id com_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire_likes ADD CONSTRAINT commentaire_likes_ibfk_1 FOREIGN KEY (user_id) REFERENCES utilisateur (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_likes ADD CONSTRAINT commentaire_likes_ibfk_2 FOREIGN KEY (com_id) REFERENCES commentaire (idcom) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_reported DROP FOREIGN KEY FK_70139D7FFA122EBC');
        $this->addSql('ALTER TABLE commentaire_reported DROP FOREIGN KEY FK_70139D7F6B3CA4B');
        $this->addSql('ALTER TABLE commentaire_reported CHANGE id_com id_com INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire_reported ADD CONSTRAINT commentaire_reported_ibfk_1 FOREIGN KEY (id_com) REFERENCES commentaire (idcom) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire_reported ADD CONSTRAINT commentaire_reported_ibfk_2 FOREIGN KEY (id_user) REFERENCES utilisateur (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement CHANGE auteur auteur INT NOT NULL, CHANGE gouvernorat gouvernorat INT NOT NULL');
        $this->addSql('ALTER TABLE hotel CHANGE gouvernorat gouvernorat INT NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC274457C12B');
        $this->addSql('DROP INDEX IDX_29A5EC274457C12B ON produit');
        $this->addSql('ALTER TABLE produit DROP price, CHANGE gouvernorat gouvernorat INT NOT NULL');
        $this->addSql('ALTER TABLE rate_evenement DROP FOREIGN KEY FK_AA202C706B3CA4B');
        $this->addSql('ALTER TABLE rate_evenement CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE rate_evenement ADD CONSTRAINT rate_evenement_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rate_hotel CHANGE id_hotel id_hotel INT NOT NULL');
    }
}
