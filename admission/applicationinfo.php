<?php

// Global variable for table object
$application = NULL;

//
// Table class for application
//
class capplication extends cTable {
	var $program_choice;
	var $full_name;
	var $secondary_School;
	var $graduation_year;
	var $index_number;
	var $ss_course;
	var $aggregate;
	var $certificate;
	var $upload_certificate;
	var $application_status;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'application';
		$this->TableName = 'application';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`application`";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = TRUE; // Allow detail add
		$this->DetailEdit = TRUE; // Allow detail edit
		$this->DetailView = TRUE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// program_choice
		$this->program_choice = new cField('application', 'application', 'x_program_choice', 'program_choice', '`program_choice`', '`program_choice`', 200, -1, FALSE, '`program_choice`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->program_choice->Sortable = TRUE; // Allow sort
		$this->program_choice->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->program_choice->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['program_choice'] = &$this->program_choice;

		// full name
		$this->full_name = new cField('application', 'application', 'x_full_name', 'full name', '`full name`', '`full name`', 200, -1, FALSE, '`full name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->full_name->Sortable = TRUE; // Allow sort
		$this->fields['full name'] = &$this->full_name;

		// secondary_School
		$this->secondary_School = new cField('application', 'application', 'x_secondary_School', 'secondary_School', '`secondary_School`', '`secondary_School`', 200, -1, FALSE, '`secondary_School`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->secondary_School->Sortable = TRUE; // Allow sort
		$this->fields['secondary_School'] = &$this->secondary_School;

		// graduation_year
		$this->graduation_year = new cField('application', 'application', 'x_graduation_year', 'graduation_year', '`graduation_year`', ew_CastDateFieldForLike('`graduation_year`', 0, "DB"), 133, 0, FALSE, '`graduation_year`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->graduation_year->Sortable = TRUE; // Allow sort
		$this->graduation_year->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['graduation_year'] = &$this->graduation_year;

		// index_number
		$this->index_number = new cField('application', 'application', 'x_index_number', 'index_number', '`index_number`', '`index_number`', 200, -1, FALSE, '`index_number`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->index_number->Sortable = TRUE; // Allow sort
		$this->fields['index_number'] = &$this->index_number;

		// ss_course
		$this->ss_course = new cField('application', 'application', 'x_ss_course', 'ss_course', '`ss_course`', '`ss_course`', 200, -1, FALSE, '`ss_course`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->ss_course->Sortable = TRUE; // Allow sort
		$this->fields['ss_course'] = &$this->ss_course;

		// aggregate
		$this->aggregate = new cField('application', 'application', 'x_aggregate', 'aggregate', '`aggregate`', '`aggregate`', 3, -1, FALSE, '`aggregate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->aggregate->Sortable = TRUE; // Allow sort
		$this->aggregate->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['aggregate'] = &$this->aggregate;

		// certificate
		$this->certificate = new cField('application', 'application', 'x_certificate', 'certificate', '`certificate`', '`certificate`', 200, -1, FALSE, '`certificate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->certificate->Sortable = TRUE; // Allow sort
		$this->fields['certificate'] = &$this->certificate;

		// upload_certificate
		$this->upload_certificate = new cField('application', 'application', 'x_upload_certificate', 'upload_certificate', '`upload_certificate`', '`upload_certificate`', 200, -1, TRUE, '`upload_certificate`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->upload_certificate->Sortable = TRUE; // Allow sort
		$this->fields['upload_certificate'] = &$this->upload_certificate;

		// application_status
		$this->application_status = new cField('application', 'application', 'x_application_status', 'application_status', '`application_status`', '`application_status`', 200, -1, FALSE, '`application_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->application_status->Sortable = TRUE; // Allow sort
		$this->application_status->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->application_status->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->fields['application_status'] = &$this->application_status;
	}

	// Field Visibility
	function GetFieldVisibility($fldparm) {
		global $Security;
		return $this->$fldparm->Visible; // Returns original value
	}

	// Column CSS classes
	var $LeftColumnClass = "col-sm-2 control-label ewLabel";
	var $RightColumnClass = "col-sm-10";
	var $OffsetColumnClass = "col-sm-10 col-sm-offset-2";

	// Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
	function SetLeftColumnClass($class) {
		if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
			$this->LeftColumnClass = $class . " control-label ewLabel";
			$this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - intval($match[2]));
			$this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace($match[1], $match[1] + "-offset", $class);
		}
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	var $_SqlFrom = "";

	function getSqlFrom() { // From
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`application`";
	}

	function SqlFrom() { // For backward compatibility
		return $this->getSqlFrom();
	}

	function setSqlFrom($v) {
		$this->_SqlFrom = $v;
	}
	var $_SqlSelect = "";

	function getSqlSelect() { // Select
		return ($this->_SqlSelect <> "") ? $this->_SqlSelect : "SELECT * FROM " . $this->getSqlFrom();
	}

	function SqlSelect() { // For backward compatibility
		return $this->getSqlSelect();
	}

	function setSqlSelect($v) {
		$this->_SqlSelect = $v;
	}
	var $_SqlWhere = "";

	function getSqlWhere() { // Where
		$sWhere = ($this->_SqlWhere <> "") ? $this->_SqlWhere : "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlWhere() { // For backward compatibility
		return $this->getSqlWhere();
	}

	function setSqlWhere($v) {
		$this->_SqlWhere = $v;
	}
	var $_SqlGroupBy = "";

	function getSqlGroupBy() { // Group By
		return ($this->_SqlGroupBy <> "") ? $this->_SqlGroupBy : "";
	}

	function SqlGroupBy() { // For backward compatibility
		return $this->getSqlGroupBy();
	}

	function setSqlGroupBy($v) {
		$this->_SqlGroupBy = $v;
	}
	var $_SqlHaving = "";

	function getSqlHaving() { // Having
		return ($this->_SqlHaving <> "") ? $this->_SqlHaving : "";
	}

	function SqlHaving() { // For backward compatibility
		return $this->getSqlHaving();
	}

	function setSqlHaving($v) {
		$this->_SqlHaving = $v;
	}
	var $_SqlOrderBy = "";

	function getSqlOrderBy() { // Order By
		return ($this->_SqlOrderBy <> "") ? $this->_SqlOrderBy : "";
	}

	function SqlOrderBy() { // For backward compatibility
		return $this->getSqlOrderBy();
	}

	function setSqlOrderBy($v) {
		$this->_SqlOrderBy = $v;
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->getSqlSelect(), $this->getSqlWhere(),
			$this->getSqlGroupBy(), $this->getSqlHaving(), $this->getSqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$filter = $this->CurrentFilter;
		$filter = $this->ApplyUserIDFilters($filter);
		$sort = $this->getSessionOrderBy();
		return $this->GetSQL($filter, $sort);
	}

	// Table SQL with List page filter
	var $UseSessionForListSQL = TRUE;

	function ListSQL() {
		$sFilter = $this->UseSessionForListSQL ? $this->getSessionWhere() : "";
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$this->Recordset_Selecting($sFilter);
		$sSelect = $this->getSqlSelect();
		$sSort = $this->UseSessionForListSQL ? $this->getSessionOrderBy() : "";
		return ew_BuildSelectSql($sSelect, $this->getSqlWhere(), $this->getSqlGroupBy(),
			$this->getSqlHaving(), $this->getSqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->getSqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sql) {
		$cnt = -1;
		$pattern = "/^SELECT \* FROM/i";
		if (($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') && preg_match($pattern, $sql)) {
			$sql = "SELECT COUNT(*) FROM" . preg_replace($pattern, "", $sql);
		} else {
			$sql = "SELECT COUNT(*) FROM (" . $sql . ") EW_COUNT_TABLE";
		}
		$conn = &$this->Connection();
		if ($rs = $conn->Execute($sql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($filter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $filter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function ListRecordCount() {
		$filter = $this->getSessionWhere();
		ew_AddFilter($filter, $this->CurrentFilter);
		$filter = $this->ApplyUserIDFilters($filter);
		$this->Recordset_Selecting($filter);
		$select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : "SELECT * FROM " . $this->getSqlFrom();
		$groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
		$having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
		$sql = ew_BuildSelectSql($select, $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
		$cnt = $this->TryGetRecordCount($sql);
		if ($cnt == -1) {
			$conn = &$this->Connection();
			if ($rs = $conn->Execute($sql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// INSERT statement
	function InsertSQL(&$rs) {
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$names = preg_replace('/,+$/', "", $names);
		$values = preg_replace('/,+$/', "", $values);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		$conn = &$this->Connection();
		$bInsert = $conn->Execute($this->InsertSQL($rs));
		if ($bInsert) {
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType, $this->DBID) . ",";
		}
		$sql = preg_replace('/,+$/', "", $sql);
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL, $curfilter = TRUE) {
		$conn = &$this->Connection();
		$bUpdate = $conn->Execute($this->UpdateSQL($rs, $where, $curfilter));
		return $bUpdate;
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if (is_array($where))
			$where = $this->ArrayToFilter($where);
		if ($rs) {
			if (array_key_exists('index_number', $rs))
				ew_AddFilter($where, ew_QuotedName('index_number', $this->DBID) . '=' . ew_QuotedValue($rs['index_number'], $this->index_number->FldDataType, $this->DBID));
		}
		$filter = ($curfilter) ? $this->CurrentFilter : "";
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "", $curfilter = TRUE) {
		$bDelete = TRUE;
		$conn = &$this->Connection();
		if ($bDelete)
			$bDelete = $conn->Execute($this->DeleteSQL($rs, $where, $curfilter));
		return $bDelete;
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`index_number` = '@index_number@'";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (is_null($this->index_number->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@index_number@", ew_AdjustSql($this->index_number->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "applicationlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "applicationview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "applicationedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "applicationadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "applicationlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("applicationview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("applicationview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "applicationadd.php?" . $this->UrlParm($parm);
		else
			$url = "applicationadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("applicationedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("applicationadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("applicationdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "index_number:" . ew_VarToJson($this->index_number->CurrentValue, "string", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->index_number->CurrentValue)) {
			$sUrl .= "index_number=" . urlencode($this->index_number->CurrentValue);
		} else {
			return "javascript:ew_Alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return $this->AddMasterUrl(ew_CurrentPage() . "?" . $sUrlParm);
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = $_POST["key_m"];
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = $_GET["key_m"];
			$cnt = count($arKeys);
		} elseif (!empty($_GET) || !empty($_POST)) {
			$isPost = ew_IsPost();
			if ($isPost && isset($_POST["index_number"]))
				$arKeys[] = $_POST["index_number"];
			elseif (isset($_GET["index_number"]))
				$arKeys[] = $_GET["index_number"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				$ar[] = $key;
			}
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->index_number->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($filter) {

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $filter;
		//$sql = $this->SQL();

		$sql = $this->GetSQL($filter, "");
		$conn = &$this->Connection();
		$rs = $conn->Execute($sql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->program_choice->setDbValue($rs->fields('program_choice'));
		$this->full_name->setDbValue($rs->fields('full name'));
		$this->secondary_School->setDbValue($rs->fields('secondary_School'));
		$this->graduation_year->setDbValue($rs->fields('graduation_year'));
		$this->index_number->setDbValue($rs->fields('index_number'));
		$this->ss_course->setDbValue($rs->fields('ss_course'));
		$this->aggregate->setDbValue($rs->fields('aggregate'));
		$this->certificate->setDbValue($rs->fields('certificate'));
		$this->upload_certificate->Upload->DbValue = $rs->fields('upload_certificate');
		$this->application_status->setDbValue($rs->fields('application_status'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// program_choice
		// full name
		// secondary_School
		// graduation_year
		// index_number
		// ss_course
		// aggregate
		// certificate
		// upload_certificate
		// application_status
		// program_choice

		if (strval($this->program_choice->CurrentValue) <> "") {
			$sFilterWrk = "`program`" . ew_SearchString("=", $this->program_choice->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `program`, `program` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `programs`";
		$sWhereWrk = "";
		$this->program_choice->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->program_choice, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->program_choice->ViewValue = $this->program_choice->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->program_choice->ViewValue = $this->program_choice->CurrentValue;
			}
		} else {
			$this->program_choice->ViewValue = NULL;
		}
		$this->program_choice->ViewCustomAttributes = "";

		// full name
		$this->full_name->ViewValue = $this->full_name->CurrentValue;
		$this->full_name->ViewCustomAttributes = "";

		// secondary_School
		$this->secondary_School->ViewValue = $this->secondary_School->CurrentValue;
		$this->secondary_School->ViewCustomAttributes = "";

		// graduation_year
		$this->graduation_year->ViewValue = $this->graduation_year->CurrentValue;
		$this->graduation_year->ViewValue = ew_FormatDateTime($this->graduation_year->ViewValue, 0);
		$this->graduation_year->ViewCustomAttributes = "";

		// index_number
		$this->index_number->ViewValue = $this->index_number->CurrentValue;
		$this->index_number->ViewCustomAttributes = "";

		// ss_course
		$this->ss_course->ViewValue = $this->ss_course->CurrentValue;
		$this->ss_course->ViewCustomAttributes = "";

		// aggregate
		$this->aggregate->ViewValue = $this->aggregate->CurrentValue;
		$this->aggregate->ViewCustomAttributes = "";

		// certificate
		$this->certificate->ViewValue = $this->certificate->CurrentValue;
		$this->certificate->ViewCustomAttributes = "";

		// upload_certificate
		if (!ew_Empty($this->upload_certificate->Upload->DbValue)) {
			$this->upload_certificate->ImageAlt = $this->upload_certificate->FldAlt();
			$this->upload_certificate->ViewValue = $this->upload_certificate->Upload->DbValue;
		} else {
			$this->upload_certificate->ViewValue = "";
		}
		$this->upload_certificate->ViewCustomAttributes = "";

		// application_status
		if (strval($this->application_status->CurrentValue) <> "") {
			$sFilterWrk = "`admmision_status`" . ew_SearchString("=", $this->application_status->CurrentValue, EW_DATATYPE_STRING, "");
		$sSqlWrk = "SELECT `admmision_status`, `admmision_status` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `status`";
		$sWhereWrk = "";
		$this->application_status->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->application_status, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->application_status->ViewValue = $this->application_status->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->application_status->ViewValue = $this->application_status->CurrentValue;
			}
		} else {
			$this->application_status->ViewValue = NULL;
		}
		$this->application_status->ViewCustomAttributes = "";

		// program_choice
		$this->program_choice->LinkCustomAttributes = "";
		$this->program_choice->HrefValue = "";
		$this->program_choice->TooltipValue = "";

		// full name
		$this->full_name->LinkCustomAttributes = "";
		$this->full_name->HrefValue = "";
		$this->full_name->TooltipValue = "";

		// secondary_School
		$this->secondary_School->LinkCustomAttributes = "";
		$this->secondary_School->HrefValue = "";
		$this->secondary_School->TooltipValue = "";

		// graduation_year
		$this->graduation_year->LinkCustomAttributes = "";
		$this->graduation_year->HrefValue = "";
		$this->graduation_year->TooltipValue = "";

		// index_number
		$this->index_number->LinkCustomAttributes = "";
		$this->index_number->HrefValue = "";
		$this->index_number->TooltipValue = "";

		// ss_course
		$this->ss_course->LinkCustomAttributes = "";
		$this->ss_course->HrefValue = "";
		$this->ss_course->TooltipValue = "";

		// aggregate
		$this->aggregate->LinkCustomAttributes = "";
		$this->aggregate->HrefValue = "";
		$this->aggregate->TooltipValue = "";

		// certificate
		$this->certificate->LinkCustomAttributes = "";
		$this->certificate->HrefValue = "";
		$this->certificate->TooltipValue = "";

		// upload_certificate
		$this->upload_certificate->LinkCustomAttributes = "";
		if (!ew_Empty($this->upload_certificate->Upload->DbValue)) {
			$this->upload_certificate->HrefValue = ew_GetFileUploadUrl($this->upload_certificate, $this->upload_certificate->Upload->DbValue); // Add prefix/suffix
			$this->upload_certificate->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->upload_certificate->HrefValue = ew_FullUrl($this->upload_certificate->HrefValue, "href");
		} else {
			$this->upload_certificate->HrefValue = "";
		}
		$this->upload_certificate->HrefValue2 = $this->upload_certificate->UploadPath . $this->upload_certificate->Upload->DbValue;
		$this->upload_certificate->TooltipValue = "";
		if ($this->upload_certificate->UseColorbox) {
			if (ew_Empty($this->upload_certificate->TooltipValue))
				$this->upload_certificate->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->upload_certificate->LinkAttrs["data-rel"] = "application_x_upload_certificate";
			ew_AppendClass($this->upload_certificate->LinkAttrs["class"], "ewLightbox");
		}

		// application_status
		$this->application_status->LinkCustomAttributes = "";
		$this->application_status->HrefValue = "";
		$this->application_status->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();

		// Save data for Custom Template
		$this->Rows[] = $this->CustomTemplateFieldValues();
	}

	// Render edit row values
	function RenderEditRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

		// program_choice
		$this->program_choice->EditAttrs["class"] = "form-control";
		$this->program_choice->EditCustomAttributes = "";

		// full name
		$this->full_name->EditAttrs["class"] = "form-control";
		$this->full_name->EditCustomAttributes = "";
		$this->full_name->EditValue = $this->full_name->CurrentValue;
		$this->full_name->PlaceHolder = ew_RemoveHtml($this->full_name->FldCaption());

		// secondary_School
		$this->secondary_School->EditAttrs["class"] = "form-control";
		$this->secondary_School->EditCustomAttributes = "";
		$this->secondary_School->EditValue = $this->secondary_School->CurrentValue;
		$this->secondary_School->PlaceHolder = ew_RemoveHtml($this->secondary_School->FldCaption());

		// graduation_year
		$this->graduation_year->EditAttrs["class"] = "form-control";
		$this->graduation_year->EditCustomAttributes = "";
		$this->graduation_year->EditValue = ew_FormatDateTime($this->graduation_year->CurrentValue, 8);
		$this->graduation_year->PlaceHolder = ew_RemoveHtml($this->graduation_year->FldCaption());

		// index_number
		$this->index_number->EditAttrs["class"] = "form-control";
		$this->index_number->EditCustomAttributes = "";
		$this->index_number->EditValue = $this->index_number->CurrentValue;
		$this->index_number->ViewCustomAttributes = "";

		// ss_course
		$this->ss_course->EditAttrs["class"] = "form-control";
		$this->ss_course->EditCustomAttributes = "";
		$this->ss_course->EditValue = $this->ss_course->CurrentValue;
		$this->ss_course->PlaceHolder = ew_RemoveHtml($this->ss_course->FldCaption());

		// aggregate
		$this->aggregate->EditAttrs["class"] = "form-control";
		$this->aggregate->EditCustomAttributes = "";
		$this->aggregate->EditValue = $this->aggregate->CurrentValue;
		$this->aggregate->PlaceHolder = ew_RemoveHtml($this->aggregate->FldCaption());

		// certificate
		$this->certificate->EditAttrs["class"] = "form-control";
		$this->certificate->EditCustomAttributes = "";
		$this->certificate->EditValue = $this->certificate->CurrentValue;
		$this->certificate->PlaceHolder = ew_RemoveHtml($this->certificate->FldCaption());

		// upload_certificate
		$this->upload_certificate->EditAttrs["class"] = "form-control";
		$this->upload_certificate->EditCustomAttributes = "";
		if (!ew_Empty($this->upload_certificate->Upload->DbValue)) {
			$this->upload_certificate->ImageAlt = $this->upload_certificate->FldAlt();
			$this->upload_certificate->EditValue = $this->upload_certificate->Upload->DbValue;
		} else {
			$this->upload_certificate->EditValue = "";
		}
		if (!ew_Empty($this->upload_certificate->CurrentValue))
			$this->upload_certificate->Upload->FileName = $this->upload_certificate->CurrentValue;

		// application_status
		$this->application_status->EditAttrs["class"] = "form-control";
		$this->application_status->EditCustomAttributes = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {

		// Call Row Rendered event
		$this->Row_Rendered();
	}
	var $ExportDoc;

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;
		if (!$Doc->ExportCustom) {

			// Write header
			$Doc->ExportTableHeader();
			if ($Doc->Horizontal) { // Horizontal format, write header
				$Doc->BeginExportRow();
				if ($ExportPageType == "view") {
					if ($this->program_choice->Exportable) $Doc->ExportCaption($this->program_choice);
					if ($this->full_name->Exportable) $Doc->ExportCaption($this->full_name);
					if ($this->secondary_School->Exportable) $Doc->ExportCaption($this->secondary_School);
					if ($this->graduation_year->Exportable) $Doc->ExportCaption($this->graduation_year);
					if ($this->index_number->Exportable) $Doc->ExportCaption($this->index_number);
					if ($this->ss_course->Exportable) $Doc->ExportCaption($this->ss_course);
					if ($this->aggregate->Exportable) $Doc->ExportCaption($this->aggregate);
					if ($this->certificate->Exportable) $Doc->ExportCaption($this->certificate);
					if ($this->upload_certificate->Exportable) $Doc->ExportCaption($this->upload_certificate);
					if ($this->application_status->Exportable) $Doc->ExportCaption($this->application_status);
				} else {
					if ($this->program_choice->Exportable) $Doc->ExportCaption($this->program_choice);
					if ($this->full_name->Exportable) $Doc->ExportCaption($this->full_name);
					if ($this->secondary_School->Exportable) $Doc->ExportCaption($this->secondary_School);
					if ($this->graduation_year->Exportable) $Doc->ExportCaption($this->graduation_year);
					if ($this->index_number->Exportable) $Doc->ExportCaption($this->index_number);
					if ($this->ss_course->Exportable) $Doc->ExportCaption($this->ss_course);
					if ($this->aggregate->Exportable) $Doc->ExportCaption($this->aggregate);
					if ($this->certificate->Exportable) $Doc->ExportCaption($this->certificate);
					if ($this->upload_certificate->Exportable) $Doc->ExportCaption($this->upload_certificate);
					if ($this->application_status->Exportable) $Doc->ExportCaption($this->application_status);
				}
				$Doc->EndExportRow();
			}
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				if (!$Doc->ExportCustom) {
					$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
					if ($ExportPageType == "view") {
						if ($this->program_choice->Exportable) $Doc->ExportField($this->program_choice);
						if ($this->full_name->Exportable) $Doc->ExportField($this->full_name);
						if ($this->secondary_School->Exportable) $Doc->ExportField($this->secondary_School);
						if ($this->graduation_year->Exportable) $Doc->ExportField($this->graduation_year);
						if ($this->index_number->Exportable) $Doc->ExportField($this->index_number);
						if ($this->ss_course->Exportable) $Doc->ExportField($this->ss_course);
						if ($this->aggregate->Exportable) $Doc->ExportField($this->aggregate);
						if ($this->certificate->Exportable) $Doc->ExportField($this->certificate);
						if ($this->upload_certificate->Exportable) $Doc->ExportField($this->upload_certificate);
						if ($this->application_status->Exportable) $Doc->ExportField($this->application_status);
					} else {
						if ($this->program_choice->Exportable) $Doc->ExportField($this->program_choice);
						if ($this->full_name->Exportable) $Doc->ExportField($this->full_name);
						if ($this->secondary_School->Exportable) $Doc->ExportField($this->secondary_School);
						if ($this->graduation_year->Exportable) $Doc->ExportField($this->graduation_year);
						if ($this->index_number->Exportable) $Doc->ExportField($this->index_number);
						if ($this->ss_course->Exportable) $Doc->ExportField($this->ss_course);
						if ($this->aggregate->Exportable) $Doc->ExportField($this->aggregate);
						if ($this->certificate->Exportable) $Doc->ExportField($this->certificate);
						if ($this->upload_certificate->Exportable) $Doc->ExportField($this->upload_certificate);
						if ($this->application_status->Exportable) $Doc->ExportField($this->application_status);
					}
					$Doc->EndExportRow($RowCnt);
				}
			}

			// Call Row Export server event
			if ($Doc->ExportCustom)
				$this->Row_Export($Recordset->fields);
			$Recordset->MoveNext();
		}
		if (!$Doc->ExportCustom) {
			$Doc->ExportTableFooter();
		}
	}

	// Get auto fill value
	function GetAutoFill($id, $val) {
		$rsarr = array();
		$rowcnt = 0;

		// Output
		if (is_array($rsarr) && $rowcnt > 0) {
			$fldcnt = count($rsarr[0]);
			for ($i = 0; $i < $rowcnt; $i++) {
				for ($j = 0; $j < $fldcnt; $j++) {
					$str = strval($rsarr[$i][$j]);
					$str = ew_ConvertToUtf8($str);
					if (isset($post["keepCRLF"])) {
						$str = str_replace(array("\r", "\n"), array("\\r", "\\n"), $str);
					} else {
						$str = str_replace(array("\r", "\n"), array(" ", " "), $str);
					}
					$rsarr[$i][$j] = $str;
				}
			}
			return ew_ArrayToJson($rsarr);
		} else {
			return FALSE;
		}
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Grid Inserting event
	function Grid_Inserting() {

		// Enter your code here
		// To reject grid insert, set return value to FALSE

		return TRUE;
	}

	// Grid Inserted event
	function Grid_Inserted($rsnew) {

		//echo "Grid Inserted";
	}

	// Grid Updating event
	function Grid_Updating($rsold) {

		// Enter your code here
		// To reject grid update, set return value to FALSE

		return TRUE;
	}

	// Grid Updated event
	function Grid_Updated($rsold, $rsnew) {

		//echo "Grid Updated";
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		//var_dump($fld->FldName, $fld->LookupFilters, $filter); // Uncomment to view the filter
		// Enter your code here

	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>);

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
