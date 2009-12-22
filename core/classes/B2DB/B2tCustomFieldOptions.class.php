<?php

	/**
	 * Custom field options table
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage tables
	 */

	/**
	 * Custom field options table
	 *
	 * @package thebuggenie
	 * @subpackage tables
	 */
	class B2tCustomFieldOptions extends B2DBTable
	{

		const B2DBNAME = 'customfieldoptions';
		const ID = 'customfieldoptions.id';
		const NAME = 'customfieldoptions.cname';
		const ITEMDATA = 'customfieldoptions.itemdata';
		const OPTION_VALUE = 'customfieldoptions.option_value';
		const SORT_ORDER = 'customfieldoptions.sort_order';
		const CUSTOMFIELDS_KEY = 'customfieldoptions.customfields_key';
		const SCOPE = 'customfieldoptions.scope';

		public function __construct()
		{
			parent::__construct(self::B2DBNAME, self::ID);
			parent::_addVarchar(self::NAME, 100);
			parent::_addVarchar(self::OPTION_VALUE, 100);
			parent::_addVarchar(self::ITEMDATA, 100);
			parent::_addInteger(self::SORT_ORDER, 100);
			parent::_addForeignKeyColumn(self::CUSTOMFIELDS_KEY, B2DB::getTable('B2tCustomFields'), B2tCustomFields::FIELD_KEY);
			parent::_addForeignKeyColumn(self::SCOPE, B2DB::getTable('B2tScopes'), B2tScopes::ID);
		}

		public function createNew($key, $name, $value, $itemdata = null, $scope = null)
		{
			$scope = ($scope === null) ? BUGScontext::getScope()->getID() : $scope;

			$crit = $this->getCriteria();
			$crit->addInsert(self::NAME, $name);
			$crit->addInsert(self::OPTION_VALUE, $value);
			$crit->addInsert(self::CUSTOMFIELDS_KEY, $key);
			if ($itemdata !== null)
			{
				$crit->addInsert(self::ITEMDATA, $itemdata);
			}
			$crit->addInsert(self::SCOPE, $scope);

			return $this->doInsert($crit);
		}

		public function getAllByKey($key)
		{
			$crit = $this->getCriteria();
			$crit->addWhere(self::CUSTOMFIELDS_KEY, $key);
			$crit->addWhere(self::SCOPE, BUGScontext::getScope()->getID());

			$res = $this->doSelect($crit);
			
			$retval = array();

			if ($res)
			{
				while ($row = $res->getNextRow())
				{
					$retval[$row->get(self::ID)] = $row;
				}
			}

			return $retval;
		}

		public function saveById($name, $value, $itemdata, $id)
		{
			$crit = $this->getCriteria();
			$crit->addUpdate(self::NAME, $name);
			$crit->addUpdate(self::OPTION_VALUE, $value);
			$crit->addUpdate(self::ITEMDATA, $itemdata);

			$res = $this->doUpdateById($crit, $id);
		}

	}
