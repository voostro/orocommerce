<?php

namespace Oro\Bundle\ShippingBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class OroShippingBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_1';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createOrob2BShippingFreightClassTable($schema);
        $this->createOrob2BShippingLengthUnitTable($schema);
        $this->createOrob2BShippingOrigWarehouseTable($schema);
        $this->createOrob2BShippingProductOptsTable($schema);
        $this->createOrob2BShippingWeightUnitTable($schema);
        $this->createOrob2BShippingRuleTable($schema);
        $this->createOrob2BShippingRuleDestinationTable($schema);
        $this->createOrob2BShippingRuleConfigTable($schema);
        $this->createOrob2BShipFlatRateRuleCnfTable($schema);

        /** Foreign keys generation **/
        $this->addOrob2BShippingRuleConfigForeignKeys($schema);
        $this->addOrob2BShipFlatRateRuleCnfForeignKeys($schema);
        $this->addOrob2BShippingOrigWarehouseForeignKeys($schema);
        $this->addOrob2BShippingProductOptsForeignKeys($schema);
        $this->addOrob2BShippingRuleDestinationForeignKeys($schema);
    }
    /**
     * Create orob2b_shipping_freight_class table
     *
     * @param Schema $schema
     */
    protected function createOrob2BShippingFreightClassTable(Schema $schema)
    {
        $table = $schema->createTable('orob2b_shipping_freight_class');
        $table->addColumn('code', 'string', ['length' => 255]);
        $table->setPrimaryKey(['code']);
    }

    /**
     * Create orob2b_shipping_length_unit table
     *
     * @param Schema $schema
     */
    protected function createOrob2BShippingLengthUnitTable(Schema $schema)
    {
        $table = $schema->createTable('orob2b_shipping_length_unit');
        $table->addColumn('code', 'string', ['length' => 255]);
        $table->addColumn('conversion_rates', 'array', ['notnull' => false, 'comment' => '(DC2Type:array)']);
        $table->setPrimaryKey(['code']);
    }

    /**
     * Create orob2b_shipping_orig_warehouse table
     *
     * @param Schema $schema
     */
    protected function createOrob2BShippingOrigWarehouseTable(Schema $schema)
    {
        $table = $schema->createTable('orob2b_shipping_orig_warehouse');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('region_code', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('warehouse_id', 'integer', []);
        $table->addColumn('country_code', 'string', ['notnull' => false, 'length' => 2]);
        $table->addColumn('label', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('street', 'string', ['notnull' => false, 'length' => 500]);
        $table->addColumn('street2', 'string', ['notnull' => false, 'length' => 500]);
        $table->addColumn('city', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('postal_code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('organization', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('region_text', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('name_prefix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('first_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('middle_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('last_name', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('name_suffix', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('created', 'datetime', []);
        $table->addColumn('updated', 'datetime', []);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['warehouse_id']);
    }

    /**
     * Create orob2b_shipping_product_opts table
     *
     * @param Schema $schema
     */
    protected function createOrob2BShippingProductOptsTable(Schema $schema)
    {
        $table = $schema->createTable('orob2b_shipping_product_opts');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('freight_class_code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('product_id', 'integer');
        $table->addColumn('product_unit_code', 'string', ['length' => 255]);
        $table->addColumn('dimensions_unit_code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('weight_unit_code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('weight_value', 'float', ['notnull' => false]);
        $table->addColumn('dimensions_length', 'float', ['notnull' => false]);
        $table->addColumn('dimensions_width', 'float', ['notnull' => false]);
        $table->addColumn('dimensions_height', 'float', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(
            ['product_id', 'product_unit_code'],
            'shipping_product_opts__product_id__product_unit_code__uidx'
        );
    }

    /**
     * Create orob2b_shipping_rule_config table
     *
     * @param Schema $schema
     */
    protected function createOrob2BShippingRuleConfigTable(Schema $schema)
    {
        $table = $schema->createTable('orob2b_shipping_rule_config');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('rule_id', 'integer', []);
        $table->addColumn('type', 'string', ['length' => 255]);
        $table->addColumn('method', 'string', ['length' => 255]);
        $table->addColumn('entity_name', 'string', ['length' => 255]);
        $table->addColumn('enabled', 'boolean', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create orob2b_ship_flat_rate_rule_cnf table
     *
     * @param Schema $schema
     */
    protected function createOrob2BShipFlatRateRuleCnfTable(Schema $schema)
    {
        $table = $schema->createTable('orob2b_ship_flat_rate_rule_cnf');
        $table->addColumn('id', 'integer', []);
        $table->addColumn('value', 'money', ['precision' => 19, 'scale' => 4, 'comment' => '(DC2Type:money)']);
        $table->addColumn('handling_fee_value', 'money', [
            'notnull' => false,
            'precision' => 19,
            'scale' => 4,
            'comment' => '(DC2Type:money)'
        ]);
        $table->addColumn('processing_type', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Create orob2b_shipping_weight_unit table
     *
     * @param Schema $schema
     */
    protected function createOrob2BShippingWeightUnitTable(Schema $schema)
    {
        $table = $schema->createTable('orob2b_shipping_weight_unit');
        $table->addColumn('code', 'string', ['length' => 255]);
        $table->addColumn('conversion_rates', 'array', ['notnull' => false, 'comment' => '(DC2Type:array)']);
        $table->setPrimaryKey(['code']);
    }

    /**
     * Create orob2b_shipping_rule table
     *
     * @param Schema $schema
     */
    protected function createOrob2BShippingRuleTable(Schema $schema)
    {
        $table = $schema->createTable('orob2b_shipping_rule');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'text', ['notnull' => true]);
        $table->addColumn('enabled', 'boolean', ['notnull' => true, 'default' => true]);
        $table->addColumn('priority', 'integer', ['notnull' => true]);
        $table->addColumn('conditions', 'text', ['notnull' => false]);
        $table->addColumn('currency', 'string', ['notnull' => false, 'length' => 3]);
        $table->addColumn('stop_processing', 'boolean', ['default' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['enabled', 'currency'], 'orob2b_shipping_rl_en_cur_idx', []);
    }

    /**
     * Create orob2b_shipping_rl_destination table
     *
     * @param Schema $schema
     */
    protected function createOrob2BShippingRuleDestinationTable(Schema $schema)
    {
        $table = $schema->createTable('orob2b_shipping_rl_destination');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('country_code', 'string', ['length' => 2]);
        $table->addColumn('region_code', 'string', ['notnull' => false, 'length' => 16]);
        $table->addColumn('postal_code', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('region_text', 'string', ['notnull' => false, 'length' => 255]);
        $table->addColumn('rule_id', 'integer', []);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Add orob2b_shipping_orig_warehouse foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOrob2BShippingOrigWarehouseForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('orob2b_shipping_orig_warehouse');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_region'),
            ['region_code'],
            ['combined_code'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('orob2b_warehouse'),
            ['warehouse_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_country'),
            ['country_code'],
            ['iso2_code'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }

    /**
     * Add orob2b_shipping_product_opts foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOrob2BShippingProductOptsForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('orob2b_shipping_product_opts');
        $table->addForeignKeyConstraint(
            $schema->getTable('orob2b_shipping_freight_class'),
            ['freight_class_code'],
            ['code'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('orob2b_product'),
            ['product_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('orob2b_product_unit'),
            ['product_unit_code'],
            ['code'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('orob2b_shipping_length_unit'),
            ['dimensions_unit_code'],
            ['code'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('orob2b_shipping_weight_unit'),
            ['weight_unit_code'],
            ['code'],
            ['onDelete' => null, 'onUpdate' => null]
        );
    }

    /**
     * Add orob2b_shipping_rl_destination foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOrob2BShippingRuleDestinationForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('orob2b_shipping_rl_destination');
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_country'),
            ['country_code'],
            ['iso2_code'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('oro_dictionary_region'),
            ['region_code'],
            ['combined_code'],
            ['onDelete' => null, 'onUpdate' => null]
        );
        $table->addForeignKeyConstraint(
            $schema->getTable('orob2b_shipping_rule'),
            ['rule_id'],
            ['id'],
            ['onDelete' => 'CASCADE', 'onUpdate' => null]
        );
    }

    /**
     * Add orob2b_shipping_rule_config foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOrob2BShippingRuleConfigForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('orob2b_shipping_rule_config');
        $table->addForeignKeyConstraint(
            $schema->getTable('orob2b_shipping_rule'),
            ['rule_id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
    }

    /**
     * Add orob2b_ship_flat_rate_rule_cnf foreign keys.
     *
     * @param Schema $schema
     */
    protected function addOrob2BShipFlatRateRuleCnfForeignKeys(Schema $schema)
    {
        $table = $schema->getTable('orob2b_ship_flat_rate_rule_cnf');
        $table->addForeignKeyConstraint(
            $schema->getTable('orob2b_shipping_rule_config'),
            ['id'],
            ['id'],
            ['onUpdate' => null, 'onDelete' => 'CASCADE']
        );
    }
}