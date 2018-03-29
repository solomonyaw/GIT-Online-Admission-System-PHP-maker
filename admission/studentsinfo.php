<?php

// Global variable for table object
$students = NULL;

//
// Table class for students
//
class cstudents extends cTable {
	var $StudentID;
	var $FirstName;
	var $LastName;
	var $BithDate;
	var $Address;
	var $Username;
	var $Password;
	var $Country;
	var $_Email;
	var $photo;
	var $userlevel_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'students';
		$this->TableName = 'students';
		$this->TableType = 'TABLE';

		// Update Table
		$this->UpdateTable = "`students`";
		$this->DBID = 'DB';
		$this->ExportAll = FALSE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->ExportExcelPageOrientation = ""; // Page orientation (PHPExcel only)
		$this->ExportExcelPageSize = ""; // Page size (PHPExcel only)
		$this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
		$this->ExportWordColumnWidth = NULL; // Cell width (PHPWord only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = TRUE; // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// StudentID
		$this->StudentID = new cField('students', 'students', 'x_StudentID', 'StudentID', '`StudentID`', '`StudentID`', 3, -1, FALSE, '`StudentID`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'NO');
		$this->StudentID->Sortable = TRUE; // Allow sort
		$this->StudentID->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['StudentID'] = &$this->StudentID;

		// FirstName
		$this->FirstName = new cField('students', 'students', 'x_FirstName', 'FirstName', '`FirstName`', '`FirstName`', 200, -1, FALSE, '`FirstName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->FirstName->Sortable = TRUE; // Allow sort
		$this->fields['FirstName'] = &$this->FirstName;

		// LastName
		$this->LastName = new cField('students', 'students', 'x_LastName', 'LastName', '`LastName`', '`LastName`', 200, -1, FALSE, '`LastName`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->LastName->Sortable = TRUE; // Allow sort
		$this->fields['LastName'] = &$this->LastName;

		// BithDate
		$this->BithDate = new cField('students', 'students', 'x_BithDate', 'BithDate', '`BithDate`', ew_CastDateFieldForLike('`BithDate`', 2, "DB"), 135, 2, FALSE, '`BithDate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->BithDate->Sortable = TRUE; // Allow sort
		$this->BithDate->FldDefaultErrMsg = str_replace("%s", $GLOBALS["EW_DATE_FORMAT"], $Language->Phrase("IncorrectDate"));
		$this->fields['BithDate'] = &$this->BithDate;

		// Address
		$this->Address = new cField('students', 'students', 'x_Address', 'Address', '`Address`', '`Address`', 200, -1, FALSE, '`Address`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Address->Sortable = TRUE; // Allow sort
		$this->fields['Address'] = &$this->Address;

		// Username
		$this->Username = new cField('students', 'students', 'x_Username', 'Username', '`Username`', '`Username`', 200, -1, FALSE, '`Username`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Username->Sortable = TRUE; // Allow sort
		$this->fields['Username'] = &$this->Username;

		// Password
		$this->Password = new cField('students', 'students', 'x_Password', 'Password', '`Password`', '`Password`', 200, -1, FALSE, '`Password`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Password->Sortable = TRUE; // Allow sort
		$this->fields['Password'] = &$this->Password;

		// Country
		$this->Country = new cField('students', 'students', 'x_Country', 'Country', '`Country`', '`Country`', 200, -1, FALSE, '`Country`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->Country->Sortable = TRUE; // Allow sort
		$this->fields['Country'] = &$this->Country;

		// Email
		$this->_Email = new cField('students', 'students', 'x__Email', 'Email', '`Email`', '`Email`', 200, -1, FALSE, '`Email`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'TEXT');
		$this->_Email->Sortable = TRUE; // Allow sort
		$this->fields['Email'] = &$this->_Email;

		// photo
		$this->photo = new cField('students', 'students', 'x_photo', 'photo', '`photo`', '`photo`', 200, -1, TRUE, '`photo`', FALSE, FALSE, FALSE, 'IMAGE', 'FILE');
		$this->photo->Sortable = TRUE; // Allow sort
		$this->fields['photo'] = &$this->photo;

		// userlevel_id
		$this->userlevel_id = new cField('students', 'students', 'x_userlevel_id', 'userlevel_id', '`userlevel_id`', '`userlevel_id`', 3, -1, FALSE, '`userlevel_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT', 'SELECT');
		$this->userlevel_id->Sortable = TRUE; // Allow sort
		$this->userlevel_id->UsePleaseSelect = TRUE; // Use PleaseSelect by default
		$this->userlevel_id->PleaseSelectText = $Language->Phrase("PleaseSelect"); // PleaseSelect text
		$this->userlevel_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['userlevel_id'] = &$this->userlevel_id;
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
		return ($this->_SqlFrom <> "") ? $this->_SqlFrom : "`students`";
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
		global $Security;

		// Add User ID filter
		if ($Security->CurrentUserID() <> "" && !$Security->IsAdmin()) { // Non system admin
			$sFilter = $this->AddUserIDFilter($sFilter);
		}
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = $this->UserIDAllowSecurity;
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
			if (EW_ENCRYPTED_PASSWORD && $name == 'Password')
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
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

			// Get insert id if necessary
			$this->StudentID->setDbValue($conn->Insert_ID());
			$rs['StudentID'] = $this->StudentID->DbValue;
		}
		return $bInsert;
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "", $curfilter = TRUE) {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]) || $this->fields[$name]->FldIsCustom)
				continue;
			if (EW_ENCRYPTED_PASSWORD && $name == 'Password') {
				if ($value == $this->fields[$name]->OldValue) // No need to update hashed password if not changed
					continue;
				$value = (EW_CASE_SENSITIVE_PASSWORD) ? ew_EncryptPassword($value) : ew_EncryptPassword(strtolower($value));
			}
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
			if (array_key_exists('StudentID', $rs))
				ew_AddFilter($where, ew_QuotedName('StudentID', $this->DBID) . '=' . ew_QuotedValue($rs['StudentID'], $this->StudentID->FldDataType, $this->DBID));
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
		return "`StudentID` = @StudentID@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->StudentID->CurrentValue))
			return "0=1"; // Invalid key
		if (is_null($this->StudentID->CurrentValue))
			return "0=1"; // Invalid key
		else
			$sKeyFilter = str_replace("@StudentID@", ew_AdjustSql($this->StudentID->CurrentValue, $this->DBID), $sKeyFilter); // Replace key value
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
			return "studentslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// Get modal caption
	function GetModalCaption($pageName) {
		global $Language;
		if ($pageName == "studentsview.php")
			return $Language->Phrase("View");
		elseif ($pageName == "studentsedit.php")
			return $Language->Phrase("Edit");
		elseif ($pageName == "studentsadd.php")
			return $Language->Phrase("Add");
		else
			return "";
	}

	// List URL
	function GetListUrl() {
		return "studentslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			$url = $this->KeyUrl("studentsview.php", $this->UrlParm($parm));
		else
			$url = $this->KeyUrl("studentsview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
		return $this->AddMasterUrl($url);
	}

	// Add URL
	function GetAddUrl($parm = "") {
		if ($parm <> "")
			$url = "studentsadd.php?" . $this->UrlParm($parm);
		else
			$url = "studentsadd.php";
		return $this->AddMasterUrl($url);
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		$url = $this->KeyUrl("studentsedit.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
		return $this->AddMasterUrl($url);
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		$url = $this->KeyUrl("studentsadd.php", $this->UrlParm($parm));
		return $this->AddMasterUrl($url);
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		$url = $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
		return $this->AddMasterUrl($url);
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("studentsdelete.php", $this->UrlParm());
	}

	// Add master url
	function AddMasterUrl($url) {
		return $url;
	}

	function KeyToJson() {
		$json = "";
		$json .= "StudentID:" . ew_VarToJson($this->StudentID->CurrentValue, "number", "'");
		return "{" . $json . "}";
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->StudentID->CurrentValue)) {
			$sUrl .= "StudentID=" . urlencode($this->StudentID->CurrentValue);
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
			if ($isPost && isset($_POST["StudentID"]))
				$arKeys[] = $_POST["StudentID"];
			elseif (isset($_GET["StudentID"]))
				$arKeys[] = $_GET["StudentID"];
			else
				$arKeys = NULL; // Do not setup

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		if (is_array($arKeys)) {
			foreach ($arKeys as $key) {
				if (!is_numeric($key))
					continue;
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
			$this->StudentID->CurrentValue = $key;
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
		$this->StudentID->setDbValue($rs->fields('StudentID'));
		$this->FirstName->setDbValue($rs->fields('FirstName'));
		$this->LastName->setDbValue($rs->fields('LastName'));
		$this->BithDate->setDbValue($rs->fields('BithDate'));
		$this->Address->setDbValue($rs->fields('Address'));
		$this->Username->setDbValue($rs->fields('Username'));
		$this->Password->setDbValue($rs->fields('Password'));
		$this->Country->setDbValue($rs->fields('Country'));
		$this->_Email->setDbValue($rs->fields('Email'));
		$this->photo->Upload->DbValue = $rs->fields('photo');
		$this->userlevel_id->setDbValue($rs->fields('userlevel_id'));
	}

	// Render list row values
	function RenderListRow() {
		global $Security, $gsLanguage, $Language;

		// Call Row Rendering event
		$this->Row_Rendering();

	// Common render codes
		// StudentID
		// FirstName
		// LastName
		// BithDate
		// Address
		// Username
		// Password
		// Country
		// Email
		// photo
		// userlevel_id
		// StudentID

		$this->StudentID->ViewValue = $this->StudentID->CurrentValue;
		$this->StudentID->ViewCustomAttributes = "";

		// FirstName
		$this->FirstName->ViewValue = $this->FirstName->CurrentValue;
		$this->FirstName->ViewCustomAttributes = "";

		// LastName
		$this->LastName->ViewValue = $this->LastName->CurrentValue;
		$this->LastName->ViewCustomAttributes = "";

		// BithDate
		$this->BithDate->ViewValue = $this->BithDate->CurrentValue;
		$this->BithDate->ViewValue = ew_FormatDateTime($this->BithDate->ViewValue, 2);
		$this->BithDate->ViewCustomAttributes = "";

		// Address
		$this->Address->ViewValue = $this->Address->CurrentValue;
		$this->Address->ViewCustomAttributes = "";

		// Username
		$this->Username->ViewValue = $this->Username->CurrentValue;
		$this->Username->ViewCustomAttributes = "";

		// Password
		$this->Password->ViewValue = $this->Password->CurrentValue;
		$this->Password->ViewCustomAttributes = "";

		// Country
		$this->Country->ViewValue = $this->Country->CurrentValue;
		$this->Country->ViewCustomAttributes = "";

		// Email
		$this->_Email->ViewValue = $this->_Email->CurrentValue;
		$this->_Email->ViewCustomAttributes = "";

		// photo
		if (!ew_Empty($this->photo->Upload->DbValue)) {
			$this->photo->ImageWidth = 2000;
			$this->photo->ImageHeight = 200;
			$this->photo->ImageAlt = $this->photo->FldAlt();
			$this->photo->ViewValue = $this->photo->Upload->DbValue;
		} else {
			$this->photo->ViewValue = "";
		}
		$this->photo->ViewCustomAttributes = "";

		// userlevel_id
		if ($Security->CanAdmin()) { // System admin
		if (strval($this->userlevel_id->CurrentValue) <> "") {
			$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->userlevel_id->CurrentValue, EW_DATATYPE_NUMBER, "");
		$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
		$sWhereWrk = "";
		$this->userlevel_id->LookupFilters = array();
		ew_AddFilter($sWhereWrk, $sFilterWrk);
		$this->Lookup_Selecting($this->userlevel_id, $sWhereWrk); // Call Lookup Selecting
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$arwrk = array();
				$arwrk[1] = $rswrk->fields('DispFld');
				$this->userlevel_id->ViewValue = $this->userlevel_id->DisplayValue($arwrk);
				$rswrk->Close();
			} else {
				$this->userlevel_id->ViewValue = $this->userlevel_id->CurrentValue;
			}
		} else {
			$this->userlevel_id->ViewValue = NULL;
		}
		} else {
			$this->userlevel_id->ViewValue = $Language->Phrase("PasswordMask");
		}
		$this->userlevel_id->ViewCustomAttributes = "";

		// StudentID
		$this->StudentID->LinkCustomAttributes = "";
		$this->StudentID->HrefValue = "";
		$this->StudentID->TooltipValue = "";

		// FirstName
		$this->FirstName->LinkCustomAttributes = "";
		$this->FirstName->HrefValue = "";
		$this->FirstName->TooltipValue = "";

		// LastName
		$this->LastName->LinkCustomAttributes = "";
		$this->LastName->HrefValue = "";
		$this->LastName->TooltipValue = "";

		// BithDate
		$this->BithDate->LinkCustomAttributes = "";
		$this->BithDate->HrefValue = "";
		$this->BithDate->TooltipValue = "";

		// Address
		$this->Address->LinkCustomAttributes = "";
		$this->Address->HrefValue = "";
		$this->Address->TooltipValue = "";

		// Username
		$this->Username->LinkCustomAttributes = "";
		$this->Username->HrefValue = "";
		$this->Username->TooltipValue = "";

		// Password
		$this->Password->LinkCustomAttributes = "";
		$this->Password->HrefValue = "";
		$this->Password->TooltipValue = "";

		// Country
		$this->Country->LinkCustomAttributes = "";
		$this->Country->HrefValue = "";
		$this->Country->TooltipValue = "";

		// Email
		$this->_Email->LinkCustomAttributes = "";
		$this->_Email->HrefValue = "";
		$this->_Email->TooltipValue = "";

		// photo
		$this->photo->LinkCustomAttributes = "";
		if (!ew_Empty($this->photo->Upload->DbValue)) {
			$this->photo->HrefValue = ew_GetFileUploadUrl($this->photo, $this->photo->Upload->DbValue); // Add prefix/suffix
			$this->photo->LinkAttrs["target"] = ""; // Add target
			if ($this->Export <> "") $this->photo->HrefValue = ew_FullUrl($this->photo->HrefValue, "href");
		} else {
			$this->photo->HrefValue = "";
		}
		$this->photo->HrefValue2 = $this->photo->UploadPath . $this->photo->Upload->DbValue;
		$this->photo->TooltipValue = "";
		if ($this->photo->UseColorbox) {
			if (ew_Empty($this->photo->TooltipValue))
				$this->photo->LinkAttrs["title"] = $Language->Phrase("ViewImageGallery");
			$this->photo->LinkAttrs["data-rel"] = "students_x_photo";
			ew_AppendClass($this->photo->LinkAttrs["class"], "ewLightbox");
		}

		// userlevel_id
		$this->userlevel_id->LinkCustomAttributes = "";
		$this->userlevel_id->HrefValue = "";
		$this->userlevel_id->TooltipValue = "";

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

		// StudentID
		$this->StudentID->EditAttrs["class"] = "form-control";
		$this->StudentID->EditCustomAttributes = "";
		$this->StudentID->EditValue = $this->StudentID->CurrentValue;
		$this->StudentID->ViewCustomAttributes = "";

		// FirstName
		$this->FirstName->EditAttrs["class"] = "form-control";
		$this->FirstName->EditCustomAttributes = "";
		$this->FirstName->EditValue = $this->FirstName->CurrentValue;
		$this->FirstName->PlaceHolder = ew_RemoveHtml($this->FirstName->FldCaption());

		// LastName
		$this->LastName->EditAttrs["class"] = "form-control";
		$this->LastName->EditCustomAttributes = "";
		$this->LastName->EditValue = $this->LastName->CurrentValue;
		$this->LastName->PlaceHolder = ew_RemoveHtml($this->LastName->FldCaption());

		// BithDate
		$this->BithDate->EditAttrs["class"] = "form-control";
		$this->BithDate->EditCustomAttributes = "";
		$this->BithDate->EditValue = ew_FormatDateTime($this->BithDate->CurrentValue, 2);
		$this->BithDate->PlaceHolder = ew_RemoveHtml($this->BithDate->FldCaption());

		// Address
		$this->Address->EditAttrs["class"] = "form-control";
		$this->Address->EditCustomAttributes = "";
		$this->Address->EditValue = $this->Address->CurrentValue;
		$this->Address->PlaceHolder = ew_RemoveHtml($this->Address->FldCaption());

		// Username
		$this->Username->EditAttrs["class"] = "form-control";
		$this->Username->EditCustomAttributes = "";
		$this->Username->EditValue = $this->Username->CurrentValue;
		$this->Username->PlaceHolder = ew_RemoveHtml($this->Username->FldCaption());

		// Password
		$this->Password->EditAttrs["class"] = "form-control";
		$this->Password->EditCustomAttributes = "";
		$this->Password->EditValue = $this->Password->CurrentValue;
		$this->Password->PlaceHolder = ew_RemoveHtml($this->Password->FldCaption());

		// Country
		$this->Country->EditAttrs["class"] = "form-control";
		$this->Country->EditCustomAttributes = "";
		$this->Country->EditValue = $this->Country->CurrentValue;
		$this->Country->PlaceHolder = ew_RemoveHtml($this->Country->FldCaption());

		// Email
		$this->_Email->EditAttrs["class"] = "form-control";
		$this->_Email->EditCustomAttributes = "";
		$this->_Email->EditValue = $this->_Email->CurrentValue;
		$this->_Email->PlaceHolder = ew_RemoveHtml($this->_Email->FldCaption());

		// photo
		$this->photo->EditAttrs["class"] = "form-control";
		$this->photo->EditCustomAttributes = "";
		if (!ew_Empty($this->photo->Upload->DbValue)) {
			$this->photo->ImageWidth = 2000;
			$this->photo->ImageHeight = 200;
			$this->photo->ImageAlt = $this->photo->FldAlt();
			$this->photo->EditValue = $this->photo->Upload->DbValue;
		} else {
			$this->photo->EditValue = "";
		}
		if (!ew_Empty($this->photo->CurrentValue))
			$this->photo->Upload->FileName = $this->photo->CurrentValue;

		// userlevel_id
		$this->userlevel_id->EditAttrs["class"] = "form-control";
		$this->userlevel_id->EditCustomAttributes = "";
		if (!$Security->CanAdmin()) { // System admin
			$this->userlevel_id->EditValue = $Language->Phrase("PasswordMask");
		} else {
		}

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
					if ($this->StudentID->Exportable) $Doc->ExportCaption($this->StudentID);
					if ($this->FirstName->Exportable) $Doc->ExportCaption($this->FirstName);
					if ($this->LastName->Exportable) $Doc->ExportCaption($this->LastName);
					if ($this->BithDate->Exportable) $Doc->ExportCaption($this->BithDate);
					if ($this->Address->Exportable) $Doc->ExportCaption($this->Address);
					if ($this->Username->Exportable) $Doc->ExportCaption($this->Username);
					if ($this->Password->Exportable) $Doc->ExportCaption($this->Password);
					if ($this->Country->Exportable) $Doc->ExportCaption($this->Country);
					if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
					if ($this->photo->Exportable) $Doc->ExportCaption($this->photo);
					if ($this->userlevel_id->Exportable) $Doc->ExportCaption($this->userlevel_id);
				} else {
					if ($this->StudentID->Exportable) $Doc->ExportCaption($this->StudentID);
					if ($this->FirstName->Exportable) $Doc->ExportCaption($this->FirstName);
					if ($this->LastName->Exportable) $Doc->ExportCaption($this->LastName);
					if ($this->BithDate->Exportable) $Doc->ExportCaption($this->BithDate);
					if ($this->Address->Exportable) $Doc->ExportCaption($this->Address);
					if ($this->Username->Exportable) $Doc->ExportCaption($this->Username);
					if ($this->Password->Exportable) $Doc->ExportCaption($this->Password);
					if ($this->Country->Exportable) $Doc->ExportCaption($this->Country);
					if ($this->_Email->Exportable) $Doc->ExportCaption($this->_Email);
					if ($this->photo->Exportable) $Doc->ExportCaption($this->photo);
					if ($this->userlevel_id->Exportable) $Doc->ExportCaption($this->userlevel_id);
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
						if ($this->StudentID->Exportable) $Doc->ExportField($this->StudentID);
						if ($this->FirstName->Exportable) $Doc->ExportField($this->FirstName);
						if ($this->LastName->Exportable) $Doc->ExportField($this->LastName);
						if ($this->BithDate->Exportable) $Doc->ExportField($this->BithDate);
						if ($this->Address->Exportable) $Doc->ExportField($this->Address);
						if ($this->Username->Exportable) $Doc->ExportField($this->Username);
						if ($this->Password->Exportable) $Doc->ExportField($this->Password);
						if ($this->Country->Exportable) $Doc->ExportField($this->Country);
						if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
						if ($this->photo->Exportable) $Doc->ExportField($this->photo);
						if ($this->userlevel_id->Exportable) $Doc->ExportField($this->userlevel_id);
					} else {
						if ($this->StudentID->Exportable) $Doc->ExportField($this->StudentID);
						if ($this->FirstName->Exportable) $Doc->ExportField($this->FirstName);
						if ($this->LastName->Exportable) $Doc->ExportField($this->LastName);
						if ($this->BithDate->Exportable) $Doc->ExportField($this->BithDate);
						if ($this->Address->Exportable) $Doc->ExportField($this->Address);
						if ($this->Username->Exportable) $Doc->ExportField($this->Username);
						if ($this->Password->Exportable) $Doc->ExportField($this->Password);
						if ($this->Country->Exportable) $Doc->ExportField($this->Country);
						if ($this->_Email->Exportable) $Doc->ExportField($this->_Email);
						if ($this->photo->Exportable) $Doc->ExportField($this->photo);
						if ($this->userlevel_id->Exportable) $Doc->ExportField($this->userlevel_id);
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

	// User ID filter
	function UserIDFilter($userid) {
		$sUserIDFilter = '`StudentID` = ' . ew_QuotedValue($userid, EW_DATATYPE_NUMBER, EW_USER_TABLE_DBID);
		return $sUserIDFilter;
	}

	// Add User ID filter
	function AddUserIDFilter($sFilter) {
		global $Security;
		$sFilterWrk = "";
		$id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
		if (!$this->UserIDAllow($id) && !$Security->IsAdmin()) {
			$sFilterWrk = $Security->UserIDList();
			if ($sFilterWrk <> "")
				$sFilterWrk = '`StudentID` IN (' . $sFilterWrk . ')';
		}

		// Call User ID Filtering event
		$this->UserID_Filtering($sFilterWrk);
		ew_AddFilter($sFilter, $sFilterWrk);
		return $sFilter;
	}

	// User ID subquery
	function GetUserIDSubquery(&$fld, &$masterfld) {
		global $UserTableConn;
		$sWrk = "";
		$sSql = "SELECT " . $masterfld->FldExpression . " FROM `students`";
		$sFilter = $this->AddUserIDFilter("");
		if ($sFilter <> "") $sSql .= " WHERE " . $sFilter;

		// Use subquery
		if (EW_USE_SUBQUERY_FOR_MASTER_USER_ID) {
			$sWrk = $sSql;
		} else {

			// List all values
			if ($rs = $UserTableConn->Execute($sSql)) {
				while (!$rs->EOF) {
					if ($sWrk <> "") $sWrk .= ",";
					$sWrk .= ew_QuotedValue($rs->fields[0], $masterfld->FldDataType, EW_USER_TABLE_DBID);
					$rs->MoveNext();
				}
				$rs->Close();
			}
		}
		if ($sWrk <> "") {
			$sWrk = $fld->FldExpression . " IN (" . $sWrk . ")";
		}
		return $sWrk;
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
