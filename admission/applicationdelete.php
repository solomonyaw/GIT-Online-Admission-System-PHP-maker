<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "applicationinfo.php" ?>
<?php include_once "studentsinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$application_delete = NULL; // Initialize page object first

class capplication_delete extends capplication {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = '{F31DB578-461D-4551-B52B-112914F68329}';

	// Table name
	var $TableName = 'application';

	// Page object name
	var $PageObjName = 'application_delete';

	// Page headings
	var $Heading = '';
	var $Subheading = '';

	// Page heading
	function PageHeading() {
		global $Language;
		if ($this->Heading <> "")
			return $this->Heading;
		if (method_exists($this, "TableCaption"))
			return $this->TableCaption();
		return "";
	}

	// Page subheading
	function PageSubheading() {
		global $Language;
		if ($this->Subheading <> "")
			return $this->Subheading;
		if ($this->TableName)
			return $Language->Phrase($this->PageID);
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Methods to clear message
	function ClearMessage() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
	}

	function ClearFailureMessage() {
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
	}

	function ClearSuccessMessage() {
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
	}

	function ClearWarningMessage() {
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	function ClearMessages() {
		$_SESSION[EW_SESSION_MESSAGE] = "";
		$_SESSION[EW_SESSION_FAILURE_MESSAGE] = "";
		$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = "";
		$_SESSION[EW_SESSION_WARNING_MESSAGE] = "";
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sMessage;
			$html .= "<div class=\"alert alert-info ewInfo\">" . $sMessage . "</div>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sWarningMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sWarningMessage;
			$html .= "<div class=\"alert alert-warning ewWarning\">" . $sWarningMessage . "</div>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sSuccessMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sSuccessMessage;
			$html .= "<div class=\"alert alert-success ewSuccess\">" . $sSuccessMessage . "</div>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		if (method_exists($this, "Message_Showing"))
			$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			if (!$hidden)
				$sErrorMessage = "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $sErrorMessage;
			$html .= "<div class=\"alert alert-danger ewError\">" . $sErrorMessage . "</div>";
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p>" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Footer exists, display
			echo "<p>" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}
	var $Token = "";
	var $TokenTimeout = 0;
	var $CheckToken = EW_CHECK_TOKEN;
	var $CheckTokenFn = "ew_CheckToken";
	var $CreateTokenFn = "ew_CreateToken";

	// Valid Post
	function ValidPost() {
		if (!$this->CheckToken || !ew_IsPost())
			return TRUE;
		if (!isset($_POST[EW_TOKEN_NAME]))
			return FALSE;
		$fn = $this->CheckTokenFn;
		if (is_callable($fn))
			return $fn($_POST[EW_TOKEN_NAME], $this->TokenTimeout);
		return FALSE;
	}

	// Create Token
	function CreateToken() {
		global $gsToken;
		if ($this->CheckToken) {
			$fn = $this->CreateTokenFn;
			if ($this->Token == "" && is_callable($fn)) // Create token
				$this->Token = $fn();
			$gsToken = $this->Token; // Save to global variable
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language;
		global $UserTable, $UserTableConn;
		$GLOBALS["Page"] = &$this;
		$this->TokenTimeout = ew_SessionTimeoutTime();

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (application)
		if (!isset($GLOBALS["application"]) || get_class($GLOBALS["application"]) == "capplication") {
			$GLOBALS["application"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["application"];
		}

		// Table object (students)
		if (!isset($GLOBALS['students'])) $GLOBALS['students'] = new cstudents();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'application', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"]))
			$GLOBALS["gTimer"] = new cTimer();

		// Debug message
		ew_LoadDebugMsg();

		// Open connection
		if (!isset($conn))
			$conn = ew_Connect($this->DBID);

		// User table object (students)
		if (!isset($UserTable)) {
			$UserTable = new cstudents();
			$UserTableConn = Conn($UserTable->DBID);
		}
	}

	//
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsCustomExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			if ($Security->CanList())
				$this->Page_Terminate(ew_GetUrl("applicationlist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 

		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->program_choice->SetVisibility();
		$this->full_name->SetVisibility();
		$this->secondary_School->SetVisibility();
		$this->graduation_year->SetVisibility();
		$this->index_number->SetVisibility();
		$this->ss_course->SetVisibility();
		$this->aggregate->SetVisibility();
		$this->certificate->SetVisibility();
		$this->upload_certificate->SetVisibility();
		$this->application_status->SetVisibility();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();

		// Check token
		if (!$this->ValidPost()) {
			echo $Language->Phrase("InvalidPostRequest");
			$this->Page_Terminate();
			exit();
		}

		// Create Token
		$this->CreateToken();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $gsExportFile, $gTmpImages;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();

		// Export
		global $EW_EXPORT, $application;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($application);
				$doc->Text = $sContent;
				if ($this->Export == "email")
					echo $this->ExportEmail($doc->Text);
				else
					$doc->Export();
				ew_DeleteTmpImages(); // Delete temp images
				exit();
			}
		}
		$this->Page_Redirecting($url);

		// Close connection
		ew_CloseConn();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			ew_SaveDebugMsg();
			header("Location: " . $url);
		}
		exit();
	}
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $StartRowCnt = 1;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("applicationlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in application class, applicationinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} elseif (@$_GET["a_delete"] == "1") {
			$this->CurrentAction = "D"; // Delete record directly
		} else {
			$this->CurrentAction = "I"; // Display record
		}
		if ($this->CurrentAction == "D") {
			$this->SendEmail = TRUE; // Send email on delete success
			if ($this->DeleteRows()) { // Delete rows
				if ($this->getSuccessMessage() == "")
					$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
				$this->Page_Terminate($this->getReturnUrl()); // Return to caller
			} else { // Delete failed
				$this->CurrentAction = "I"; // Display record
			}
		}
		if ($this->CurrentAction == "I") { // Load records for display
			if ($this->Recordset = $this->LoadRecordset())
				$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
			if ($this->TotalRecs <= 0) { // No record found, exit
				if ($this->Recordset)
					$this->Recordset->Close();
				$this->Page_Terminate("applicationlist.php"); // Return to list
			}
		}
	}

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {

		// Load List page SQL
		$sSql = $this->ListSQL();
		$conn = &$this->Connection();

		// Load recordset
		$dbtype = ew_GetConnectionType($this->DBID);
		if ($this->UseSelectLimit) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			if ($dbtype == "MSSQL") {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset, array("_hasOrderBy" => trim($this->getOrderBy()) || trim($this->getSessionOrderBy())));
			} else {
				$rs = $conn->SelectLimit($sSql, $rowcnt, $offset);
			}
			$conn->raiseErrorFn = '';
		} else {
			$rs = ew_LoadRecordset($sSql, $conn);
		}

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
	}

	// Load row based on key values
	function LoadRow() {
		global $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql, $conn);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues($rs = NULL) {
		if ($rs && !$rs->EOF)
			$row = $rs->fields;
		else
			$row = $this->NewRow(); 

		// Call Row Selected event
		$this->Row_Selected($row);
		if (!$rs || $rs->EOF)
			return;
		$this->program_choice->setDbValue($row['program_choice']);
		$this->full_name->setDbValue($row['full name']);
		$this->secondary_School->setDbValue($row['secondary_School']);
		$this->graduation_year->setDbValue($row['graduation_year']);
		$this->index_number->setDbValue($row['index_number']);
		$this->ss_course->setDbValue($row['ss_course']);
		$this->aggregate->setDbValue($row['aggregate']);
		$this->certificate->setDbValue($row['certificate']);
		$this->upload_certificate->Upload->DbValue = $row['upload_certificate'];
		$this->upload_certificate->setDbValue($this->upload_certificate->Upload->DbValue);
		$this->application_status->setDbValue($row['application_status']);
	}

	// Return a row with default values
	function NewRow() {
		$row = array();
		$row['program_choice'] = NULL;
		$row['full name'] = NULL;
		$row['secondary_School'] = NULL;
		$row['graduation_year'] = NULL;
		$row['index_number'] = NULL;
		$row['ss_course'] = NULL;
		$row['aggregate'] = NULL;
		$row['certificate'] = NULL;
		$row['upload_certificate'] = NULL;
		$row['application_status'] = NULL;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->program_choice->DbValue = $row['program_choice'];
		$this->full_name->DbValue = $row['full name'];
		$this->secondary_School->DbValue = $row['secondary_School'];
		$this->graduation_year->DbValue = $row['graduation_year'];
		$this->index_number->DbValue = $row['index_number'];
		$this->ss_course->DbValue = $row['ss_course'];
		$this->aggregate->DbValue = $row['aggregate'];
		$this->certificate->DbValue = $row['certificate'];
		$this->upload_certificate->Upload->DbValue = $row['upload_certificate'];
		$this->application_status->DbValue = $row['application_status'];
	}

	// Render row values based on field settings
	function RenderRow() {
		global $Security, $Language, $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $Language, $Security;
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
		}
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn = &$this->Connection();
		$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		}
		$rows = ($rs) ? $rs->GetRows() : array();
		$conn->BeginTrans();

		// Clone old rows
		$rsold = $rows;
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['index_number'];
				$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		}
		if (!$DeleteRows) {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("applicationlist.php"), "", $this->TableVar, TRUE);
		$PageId = "delete";
		$Breadcrumb->Add("delete", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Setup AutoSuggest filters of a field
	function SetupAutoSuggestFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		}
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Render event
	function Page_Render() {

		//echo "Page Render";
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($application_delete)) $application_delete = new capplication_delete();

// Page init
$application_delete->Page_Init();

// Page main
$application_delete->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$application_delete->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "delete";
var CurrentForm = fapplicationdelete = new ew_Form("fapplicationdelete", "delete");

// Form_CustomValidate event
fapplicationdelete.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fapplicationdelete.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fapplicationdelete.Lists["x_program_choice"] = {"LinkField":"x_program","Ajax":true,"AutoFill":false,"DisplayFields":["x_program","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"programs"};
fapplicationdelete.Lists["x_program_choice"].Data = "<?php echo $application_delete->program_choice->LookupFilterQuery(FALSE, "delete") ?>";
fapplicationdelete.Lists["x_application_status"] = {"LinkField":"x_admmision_status","Ajax":true,"AutoFill":false,"DisplayFields":["x_admmision_status","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
fapplicationdelete.Lists["x_application_status"].Data = "<?php echo $application_delete->application_status->LookupFilterQuery(FALSE, "delete") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $application_delete->ShowPageHeader(); ?>
<?php
$application_delete->ShowMessage();
?>
<form name="fapplicationdelete" id="fapplicationdelete" class="form-inline ewForm ewDeleteForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($application_delete->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $application_delete->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="application">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($application_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="box ewBox ewGrid">
<div class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<table class="table ewTable">
	<thead>
	<tr class="ewTableHeader">
<?php if ($application->program_choice->Visible) { // program_choice ?>
		<th class="<?php echo $application->program_choice->HeaderCellClass() ?>"><span id="elh_application_program_choice" class="application_program_choice"><?php echo $application->program_choice->FldCaption() ?></span></th>
<?php } ?>
<?php if ($application->full_name->Visible) { // full name ?>
		<th class="<?php echo $application->full_name->HeaderCellClass() ?>"><span id="elh_application_full_name" class="application_full_name"><?php echo $application->full_name->FldCaption() ?></span></th>
<?php } ?>
<?php if ($application->secondary_School->Visible) { // secondary_School ?>
		<th class="<?php echo $application->secondary_School->HeaderCellClass() ?>"><span id="elh_application_secondary_School" class="application_secondary_School"><?php echo $application->secondary_School->FldCaption() ?></span></th>
<?php } ?>
<?php if ($application->graduation_year->Visible) { // graduation_year ?>
		<th class="<?php echo $application->graduation_year->HeaderCellClass() ?>"><span id="elh_application_graduation_year" class="application_graduation_year"><?php echo $application->graduation_year->FldCaption() ?></span></th>
<?php } ?>
<?php if ($application->index_number->Visible) { // index_number ?>
		<th class="<?php echo $application->index_number->HeaderCellClass() ?>"><span id="elh_application_index_number" class="application_index_number"><?php echo $application->index_number->FldCaption() ?></span></th>
<?php } ?>
<?php if ($application->ss_course->Visible) { // ss_course ?>
		<th class="<?php echo $application->ss_course->HeaderCellClass() ?>"><span id="elh_application_ss_course" class="application_ss_course"><?php echo $application->ss_course->FldCaption() ?></span></th>
<?php } ?>
<?php if ($application->aggregate->Visible) { // aggregate ?>
		<th class="<?php echo $application->aggregate->HeaderCellClass() ?>"><span id="elh_application_aggregate" class="application_aggregate"><?php echo $application->aggregate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($application->certificate->Visible) { // certificate ?>
		<th class="<?php echo $application->certificate->HeaderCellClass() ?>"><span id="elh_application_certificate" class="application_certificate"><?php echo $application->certificate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($application->upload_certificate->Visible) { // upload_certificate ?>
		<th class="<?php echo $application->upload_certificate->HeaderCellClass() ?>"><span id="elh_application_upload_certificate" class="application_upload_certificate"><?php echo $application->upload_certificate->FldCaption() ?></span></th>
<?php } ?>
<?php if ($application->application_status->Visible) { // application_status ?>
		<th class="<?php echo $application->application_status->HeaderCellClass() ?>"><span id="elh_application_application_status" class="application_application_status"><?php echo $application->application_status->FldCaption() ?></span></th>
<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php
$application_delete->RecCnt = 0;
$i = 0;
while (!$application_delete->Recordset->EOF) {
	$application_delete->RecCnt++;
	$application_delete->RowCnt++;

	// Set row properties
	$application->ResetAttrs();
	$application->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$application_delete->LoadRowValues($application_delete->Recordset);

	// Render row
	$application_delete->RenderRow();
?>
	<tr<?php echo $application->RowAttributes() ?>>
<?php if ($application->program_choice->Visible) { // program_choice ?>
		<td<?php echo $application->program_choice->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_program_choice" class="application_program_choice">
<span<?php echo $application->program_choice->ViewAttributes() ?>>
<?php echo $application->program_choice->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($application->full_name->Visible) { // full name ?>
		<td<?php echo $application->full_name->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_full_name" class="application_full_name">
<span<?php echo $application->full_name->ViewAttributes() ?>>
<?php echo $application->full_name->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($application->secondary_School->Visible) { // secondary_School ?>
		<td<?php echo $application->secondary_School->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_secondary_School" class="application_secondary_School">
<span<?php echo $application->secondary_School->ViewAttributes() ?>>
<?php echo $application->secondary_School->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($application->graduation_year->Visible) { // graduation_year ?>
		<td<?php echo $application->graduation_year->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_graduation_year" class="application_graduation_year">
<span<?php echo $application->graduation_year->ViewAttributes() ?>>
<?php echo $application->graduation_year->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($application->index_number->Visible) { // index_number ?>
		<td<?php echo $application->index_number->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_index_number" class="application_index_number">
<span<?php echo $application->index_number->ViewAttributes() ?>>
<?php echo $application->index_number->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($application->ss_course->Visible) { // ss_course ?>
		<td<?php echo $application->ss_course->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_ss_course" class="application_ss_course">
<span<?php echo $application->ss_course->ViewAttributes() ?>>
<?php echo $application->ss_course->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($application->aggregate->Visible) { // aggregate ?>
		<td<?php echo $application->aggregate->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_aggregate" class="application_aggregate">
<span<?php echo $application->aggregate->ViewAttributes() ?>>
<?php echo $application->aggregate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($application->certificate->Visible) { // certificate ?>
		<td<?php echo $application->certificate->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_certificate" class="application_certificate">
<span<?php echo $application->certificate->ViewAttributes() ?>>
<?php echo $application->certificate->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($application->upload_certificate->Visible) { // upload_certificate ?>
		<td<?php echo $application->upload_certificate->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_upload_certificate" class="application_upload_certificate">
<span>
<?php echo ew_GetFileViewTag($application->upload_certificate, $application->upload_certificate->ListViewValue()) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($application->application_status->Visible) { // application_status ?>
		<td<?php echo $application->application_status->CellAttributes() ?>>
<span id="el<?php echo $application_delete->RowCnt ?>_application_application_status" class="application_application_status">
<span<?php echo $application->application_status->ViewAttributes() ?>>
<?php echo $application->application_status->ListViewValue() ?></span>
</span>
</td>
<?php } ?>
	</tr>
<?php
	$application_delete->Recordset->MoveNext();
}
$application_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("DeleteBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $application_delete->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
</div>
</form>
<script type="text/javascript">
fapplicationdelete.Init();
</script>
<?php
$application_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$application_delete->Page_Terminate();
?>
