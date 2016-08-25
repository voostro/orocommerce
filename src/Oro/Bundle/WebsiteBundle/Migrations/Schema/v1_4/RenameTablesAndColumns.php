<?php

namespace Oro\Bundle\WebsiteBundle\Migrations\Schema\v1_4;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\EntityExtendBundle\Extend\RelationType;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtension;
use Oro\Bundle\MigrationBundle\Migration\Extension\RenameExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\FrontendBundle\Migration\UpdateExtendRelationQuery;

class RenameTablesAndColumns implements Migration, RenameExtensionAwareInterface
{
    /**
     * @var RenameExtension
     */
    private $renameExtension;

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $extension = $this->renameExtension;

        // notes
        $notes = $schema->getTable('oro_note');

        $notes->removeForeignKey('FK_BA066CE1271A24E0');
        $extension->renameColumn($schema, $queries, $notes, 'website_63ea35fe_id', 'website_eb2ef553_id');
        $extension->addForeignKeyConstraint(
            $schema,
            $queries,
            'oro_note',
            'orob2b_website',
            ['website_eb2ef553_id'],
            ['id'],
            ['onDelete' => 'SET NULL']
        );
        $queries->addQuery(new UpdateExtendRelationQuery(
            'Oro\Bundle\NoteBundle\Entity\Note',
            'Oro\Bundle\WebsiteBundle\Entity\Website',
            'website_63ea35fe',
            'website_eb2ef553',
            RelationType::MANY_TO_ONE
        ));

        // rename tables
        $extension->renameTable($schema, $queries, 'orob2b_related_website', 'oro_related_website');
        $extension->renameTable($schema, $queries, 'orob2b_website', 'oro_website');

        // rename indexes
        $schema->getTable('orob2b_website')->dropIndex('idx_orob2b_website_created_at');
        $schema->getTable('orob2b_website')->dropIndex('idx_orob2b_website_updated_at');

        $extension->addIndex($schema, $queries, 'oro_website', ['created_at'], 'idx_oro_website_created_at');
        $extension->addIndex($schema, $queries, 'oro_website', ['updated_at'], 'idx_oro_website_updated_at');
    }

    /**
     * {@inheritdoc}
     */
    public function setRenameExtension(RenameExtension $renameExtension)
    {
        $this->renameExtension = $renameExtension;
    }
}
