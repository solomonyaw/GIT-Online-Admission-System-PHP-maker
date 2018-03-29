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

$application_add = NULL; // Initialize page object first

class capplication_add extends capplication {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{F31DB578-461D-4551-B52B-112914F68329}';

	// Table name
	var $TableName = 'application';

	// Page object name
	var $PageObjName = 'application_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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

		// Is modal
		$this->IsModal = (@$_GET["modal"] == "1" || @$_POST["modal"] == "1");

		// User profile
		$UserProfile = new cUserProfile();

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		if ($Security->IsLoggedIn()) $Security->TablePermission_Loaded();
		if (!$Security->CanAdd()) {
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
		// Create form object

		$objForm = new cFormObj();
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

		// Process auto fill
		if (@$_POST["ajax"] == "autofill") {
			$results = $this->GetAutoFill(@$_POST["name"], @$_POST["q"]);
			if ($results) {

				// Clean output buffer
				if (!EW_DEBUG_ENABLED && ob_get_length())
					ob_end_clean();
				echo $results;
				$this->Page_Terminate();
				exit();
			}
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

			// Handle modal response
			if ($this->IsModal) { // Show as modal
				$row = array("url" => $url, "modal" => "1");
				$pageName = ew_GetPageName($url);
				if ($pageName != $this->GetListUrl()) { // Not List page
					$row["caption"] = $this->GetModalCaption($pageName);
					if ($pageName == "applicationview.php")
						$row["view"] = "1";
				} else { // List page should not be shown as modal => error
					$row["error"] = $this->getFailureMessage();
					$this->clearFailureMessage();
				}
				header("Content-Type: application/json; charset=utf-8");
				echo ew_ConvertToUtf8(ew_ArrayToJson(array($row)));
			} else {
				ew_SaveDebugMsg();
				header("Location: " . $url);
			}
		}
		exit();
	}
	var $FormClassName = "form-horizontal ewForm ewAddForm";
	var $IsModal = FALSE;
	var $IsMobileOrModal = FALSE;
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $StartRec;
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;
		global $gbSkipHeaderFooter;

		// Check modal
		if ($this->IsModal)
			$gbSkipHeaderFooter = TRUE;
		$this->IsMobileOrModal = ew_IsMobile() || $this->IsModal;
		$this->FormClassName = "ewForm ewAddForm form-horizontal";

		// Set up current action
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["index_number"] != "") {
				$this->index_number->setQueryStringValue($_GET["index_number"]);
				$this->setKey("index_number", $this->index_number->CurrentValue); // Set up key
			} else {
				$this->setKey("index_number", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
			}
		}

		// Load old record / default values
		$loaded = $this->LoadOldRecord();

		// Load form values
		if (@$_POST["a_add"] <> "") {
			$this->LoadFormValues(); // Load form values
		}

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform current action
		switch ($this->CurrentAction) {
			case "I": // Blank record
				break;
			case "C": // Copy an existing record
				if (!$loaded) { // Record not loaded
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate("applicationlist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "applicationlist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "applicationview.php")
						$sReturnUrl = $this->GetViewUrl(); // View page, return to View page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Set up Breadcrumb
		$this->SetupBreadcrumb();

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD; // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm, $Language;

		// Get upload data
		$this->upload_certificate->Upload->Index = $objForm->Index;
		$this->upload_certificate->Upload->UploadFile();
		$this->upload_certificate->CurrentValue = $this->upload_certificate->Upload->FileName;
		$this->certificate->CurrentValue = $this->upload_certificate->Upload->ContentType;
		$this->aggregate->CurrentValue = $this->upload_certificate->Upload->FileSize;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->program_choice->CurrentValue = NULL;
		$this->program_choice->OldValue = $this->program_choice->CurrentValue;
		$this->full_name->CurrentValue = NULL;
		$this->full_name->OldValue = $this->full_name->CurrentValue;
		$this->secondary_School->CurrentValue = NULL;
		$this->secondary_School->OldValue = $this->secondary_School->CurrentValue;
		$this->graduation_year->CurrentValue = NULL;
		$this->graduation_year->OldValue = $this->graduation_year->CurrentValue;
		$this->index_number->CurrentValue = NULL;
		$this->index_number->OldValue = $this->index_number->CurrentValue;
		$this->ss_course->CurrentValue = NULL;
		$this->ss_course->OldValue = $this->ss_course->CurrentValue;
		$this->aggregate->CurrentValue = NULL;
		$this->aggregate->OldValue = $this->aggregate->CurrentValue;
		$this->aggregate->CurrentValue = NULL; // Clear file related field
		$this->certificate->CurrentValue = NULL;
		$this->certificate->OldValue = $this->certificate->CurrentValue;
		$this->certificate->CurrentValue = NULL; // Clear file related field
		$this->upload_certificate->Upload->DbValue = NULL;
		$this->upload_certificate->OldValue = $this->upload_certificate->Upload->DbValue;
		$this->upload_certificate->CurrentValue = NULL; // Clear file related field
		$this->application_status->CurrentValue = NULL;
		$this->application_status->OldValue = $this->application_status->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->program_choice->FldIsDetailKey) {
			$this->program_choice->setFormValue($objForm->GetValue("x_program_choice"));
		}
		if (!$this->full_name->FldIsDetailKey) {
			$this->full_name->setFormValue($objForm->GetValue("x_full_name"));
		}
		if (!$this->secondary_School->FldIsDetailKey) {
			$this->secondary_School->setFormValue($objForm->GetValue("x_secondary_School"));
		}
		if (!$this->graduation_year->FldIsDetailKey) {
			$this->graduation_year->setFormValue($objForm->GetValue("x_graduation_year"));
			$this->graduation_year->CurrentValue = ew_UnFormatDateTime($this->graduation_year->CurrentValue, 0);
		}
		if (!$this->index_number->FldIsDetailKey) {
			$this->index_number->setFormValue($objForm->GetValue("x_index_number"));
		}
		if (!$this->ss_course->FldIsDetailKey) {
			$this->ss_course->setFormValue($objForm->GetValue("x_ss_course"));
		}
		if (!$this->aggregate->FldIsDetailKey) {
			$this->aggregate->setFormValue($objForm->GetValue("x_aggregate"));
		}
		if (!$this->certificate->FldIsDetailKey) {
			$this->certificate->setFormValue($objForm->GetValue("x_certificate"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->program_choice->CurrentValue = $this->program_choice->FormValue;
		$this->full_name->CurrentValue = $this->full_name->FormValue;
		$this->secondary_School->CurrentValue = $this->secondary_School->FormValue;
		$this->graduation_year->CurrentValue = $this->graduation_year->FormValue;
		$this->graduation_year->CurrentValue = ew_UnFormatDateTime($this->graduation_year->CurrentValue, 0);
		$this->index_number->CurrentValue = $this->index_number->FormValue;
		$this->ss_course->CurrentValue = $this->ss_course->FormValue;
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
		$this->LoadDefaultValues();
		$row = array();
		$row['program_choice'] = $this->program_choice->CurrentValue;
		$row['full name'] = $this->full_name->CurrentValue;
		$row['secondary_School'] = $this->secondary_School->CurrentValue;
		$row['graduation_year'] = $this->graduation_year->CurrentValue;
		$row['index_number'] = $this->index_number->CurrentValue;
		$row['ss_course'] = $this->ss_course->CurrentValue;
		$row['aggregate'] = $this->aggregate->CurrentValue;
		$row['certificate'] = $this->certificate->CurrentValue;
		$row['upload_certificate'] = $this->upload_certificate->Upload->DbValue;
		$row['application_status'] = $this->application_status->CurrentValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("index_number")) <> "")
			$this->index_number->CurrentValue = $this->getKey("index_number"); // index_number
		else
			$bValidKey = FALSE;

		// Load old record
		$this->OldRecordset = NULL;
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$this->OldRecordset = ew_LoadRecordset($sSql, $conn);
		}
		$this->LoadRowValues($this->OldRecordset); // Load row values
		return $bValidKey;
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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// program_choice
			$this->program_choice->EditAttrs["class"] = "form-control";
			$this->program_choice->EditCustomAttributes = "";
			if (trim(strval($this->program_choice->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`program`" . ew_SearchString("=", $this->program_choice->CurrentValue, EW_DATATYPE_STRING, "");
			}
			$sSqlWrk = "SELECT `program`, `program` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `programs`";
			$sWhereWrk = "";
			$this->program_choice->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->program_choice, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->program_choice->EditValue = $arwrk;

			// full name
			$this->full_name->EditAttrs["class"] = "form-control";
			$this->full_name->EditCustomAttributes = "";
			$this->full_name->EditValue = ew_HtmlEncode($this->full_name->CurrentValue);
			$this->full_name->PlaceHolder = ew_RemoveHtml($this->full_name->FldCaption());

			// secondary_School
			$this->secondary_School->EditAttrs["class"] = "form-control";
			$this->secondary_School->EditCustomAttributes = "";
			$this->secondary_School->EditValue = ew_HtmlEncode($this->secondary_School->CurrentValue);
			$this->secondary_School->PlaceHolder = ew_RemoveHtml($this->secondary_School->FldCaption());

			// graduation_year
			$this->graduation_year->EditAttrs["class"] = "form-control";
			$this->graduation_year->EditCustomAttributes = "";
			$this->graduation_year->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->graduation_year->CurrentValue, 8));
			$this->graduation_year->PlaceHolder = ew_RemoveHtml($this->graduation_year->FldCaption());

			// index_number
			$this->index_number->EditAttrs["class"] = "form-control";
			$this->index_number->EditCustomAttributes = "";
			$this->index_number->EditValue = ew_HtmlEncode($this->index_number->CurrentValue);
			$this->index_number->PlaceHolder = ew_RemoveHtml($this->index_number->FldCaption());

			// ss_course
			$this->ss_course->EditAttrs["class"] = "form-control";
			$this->ss_course->EditCustomAttributes = "";
			$this->ss_course->EditValue = ew_HtmlEncode($this->ss_course->CurrentValue);
			$this->ss_course->PlaceHolder = ew_RemoveHtml($this->ss_course->FldCaption());

			// aggregate
			$this->aggregate->EditAttrs["class"] = "form-control";
			$this->aggregate->EditCustomAttributes = "";
			$this->aggregate->EditValue = ew_HtmlEncode($this->aggregate->CurrentValue);
			$this->aggregate->PlaceHolder = ew_RemoveHtml($this->aggregate->FldCaption());

			// certificate
			$this->certificate->EditAttrs["class"] = "form-control";
			$this->certificate->EditCustomAttributes = "";
			$this->certificate->EditValue = ew_HtmlEncode($this->certificate->CurrentValue);
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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->upload_certificate);

			// Add refer script
			// program_choice

			$this->program_choice->LinkCustomAttributes = "";
			$this->program_choice->HrefValue = "";

			// full name
			$this->full_name->LinkCustomAttributes = "";
			$this->full_name->HrefValue = "";

			// secondary_School
			$this->secondary_School->LinkCustomAttributes = "";
			$this->secondary_School->HrefValue = "";

			// graduation_year
			$this->graduation_year->LinkCustomAttributes = "";
			$this->graduation_year->HrefValue = "";

			// index_number
			$this->index_number->LinkCustomAttributes = "";
			$this->index_number->HrefValue = "";

			// ss_course
			$this->ss_course->LinkCustomAttributes = "";
			$this->ss_course->HrefValue = "";

			// aggregate
			$this->aggregate->LinkCustomAttributes = "";
			$this->aggregate->HrefValue = "";

			// certificate
			$this->certificate->LinkCustomAttributes = "";
			$this->certificate->HrefValue = "";

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
		}
		if ($this->RowType == EW_ROWTYPE_ADD || $this->RowType == EW_ROWTYPE_EDIT || $this->RowType == EW_ROWTYPE_SEARCH) // Add/Edit/Search row
			$this->SetupFieldTitles();

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!$this->program_choice->FldIsDetailKey && !is_null($this->program_choice->FormValue) && $this->program_choice->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->program_choice->FldCaption(), $this->program_choice->ReqErrMsg));
		}
		if (!$this->full_name->FldIsDetailKey && !is_null($this->full_name->FormValue) && $this->full_name->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->full_name->FldCaption(), $this->full_name->ReqErrMsg));
		}
		if (!$this->secondary_School->FldIsDetailKey && !is_null($this->secondary_School->FormValue) && $this->secondary_School->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->secondary_School->FldCaption(), $this->secondary_School->ReqErrMsg));
		}
		if (!$this->graduation_year->FldIsDetailKey && !is_null($this->graduation_year->FormValue) && $this->graduation_year->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->graduation_year->FldCaption(), $this->graduation_year->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->graduation_year->FormValue)) {
			ew_AddMessage($gsFormError, $this->graduation_year->FldErrMsg());
		}
		if (!$this->index_number->FldIsDetailKey && !is_null($this->index_number->FormValue) && $this->index_number->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->index_number->FldCaption(), $this->index_number->ReqErrMsg));
		}
		if (!$this->ss_course->FldIsDetailKey && !is_null($this->ss_course->FormValue) && $this->ss_course->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->ss_course->FldCaption(), $this->ss_course->ReqErrMsg));
		}
		if (!$this->aggregate->FldIsDetailKey && !is_null($this->aggregate->FormValue) && $this->aggregate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->aggregate->FldCaption(), $this->aggregate->ReqErrMsg));
		}
		if (!ew_CheckInteger($this->aggregate->FormValue)) {
			ew_AddMessage($gsFormError, $this->aggregate->FldErrMsg());
		}
		if (!$this->certificate->FldIsDetailKey && !is_null($this->certificate->FormValue) && $this->certificate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->certificate->FldCaption(), $this->certificate->ReqErrMsg));
		}
		if ($this->upload_certificate->Upload->FileName == "" && !$this->upload_certificate->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->upload_certificate->FldCaption(), $this->upload_certificate->ReqErrMsg));
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $Language, $Security;
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// program_choice
		$this->program_choice->SetDbValueDef($rsnew, $this->program_choice->CurrentValue, "", FALSE);

		// full name
		$this->full_name->SetDbValueDef($rsnew, $this->full_name->CurrentValue, "", FALSE);

		// secondary_School
		$this->secondary_School->SetDbValueDef($rsnew, $this->secondary_School->CurrentValue, "", FALSE);

		// graduation_year
		$this->graduation_year->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->graduation_year->CurrentValue, 0), ew_CurrentDate(), FALSE);

		// index_number
		$this->index_number->SetDbValueDef($rsnew, $this->index_number->CurrentValue, "", FALSE);

		// ss_course
		$this->ss_course->SetDbValueDef($rsnew, $this->ss_course->CurrentValue, "", FALSE);

		// aggregate
		// certificate
		// upload_certificate

		if ($this->upload_certificate->Visible && !$this->upload_certificate->Upload->KeepFile) {
			$this->upload_certificate->Upload->DbValue = ""; // No need to delete old file
			if ($this->upload_certificate->Upload->FileName == "") {
				$rsnew['upload_certificate'] = NULL;
			} else {
				$rsnew['upload_certificate'] = $this->upload_certificate->Upload->FileName;
			}
			$this->certificate->SetDbValueDef($rsnew, trim($this->upload_certificate->Upload->ContentType), "", FALSE);
			$this->aggregate->SetDbValueDef($rsnew, $this->upload_certificate->Upload->FileSize, 0, FALSE);
		}
		if ($this->upload_certificate->Visible && !$this->upload_certificate->Upload->KeepFile) {
			$OldFiles = ew_Empty($this->upload_certificate->Upload->DbValue) ? array() : array($this->upload_certificate->Upload->DbValue);
			if (!ew_Empty($this->upload_certificate->Upload->FileName)) {
				$NewFiles = array($this->upload_certificate->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->upload_certificate->Upload->Index < 0) ? $this->upload_certificate->FldVar : substr($this->upload_certificate->FldVar, 0, 1) . $this->upload_certificate->Upload->Index . substr($this->upload_certificate->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->upload_certificate->TblVar) . $file)) {
							$file1 = ew_UploadFileNameEx($this->upload_certificate->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->upload_certificate->TblVar) . $file1) || file_exists($this->upload_certificate->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->upload_certificate->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->upload_certificate->TblVar) . $file, ew_UploadTempPath($fldvar, $this->upload_certificate->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->upload_certificate->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->upload_certificate->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->upload_certificate->SetDbValueDef($rsnew, $this->upload_certificate->Upload->FileName, "", FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && strval($rsnew['index_number']) == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->upload_certificate->Visible && !$this->upload_certificate->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->upload_certificate->Upload->DbValue) ? array() : array($this->upload_certificate->Upload->DbValue);
					if (!ew_Empty($this->upload_certificate->Upload->FileName)) {
						$NewFiles = array($this->upload_certificate->Upload->FileName);
						$NewFiles2 = array($rsnew['upload_certificate']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->upload_certificate->Upload->Index < 0) ? $this->upload_certificate->FldVar : substr($this->upload_certificate->FldVar, 0, 1) . $this->upload_certificate->Upload->Index . substr($this->upload_certificate->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->upload_certificate->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->upload_certificate->Upload->SaveToFile($NewFiles[$i], TRUE, $i)) { // Just replace
										$this->setFailureMessage($Language->Phrase("UploadErrMsg7"));
										return FALSE;
									}
								}
							}
						}
					} else {
						$NewFiles = array();
					}
				}
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}

		// upload_certificate
		ew_CleanUploadTempPath($this->upload_certificate, $this->upload_certificate->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("applicationlist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_program_choice":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `program` AS `LinkFld`, `program` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `programs`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`program` IN ({filter_value})', "t0" => "200", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->program_choice, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			if ($sSqlWrk <> "")
				$fld->LookupFilters["s"] .= $sSqlWrk;
			break;
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($application_add)) $application_add = new capplication_add();

// Page init
$application_add->Page_Init();

// Page main
$application_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$application_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fapplicationadd = new ew_Form("fapplicationadd", "add");

// Validate form
fapplicationadd.Validate = function() {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	var $ = jQuery, fobj = this.GetForm(), $fobj = $(fobj);
	if ($fobj.find("#a_confirm").val() == "F")
		return true;
	var elm, felm, uelm, addcnt = 0;
	var $k = $fobj.find("#" + this.FormKeyCountName); // Get key_count
	var rowcnt = ($k[0]) ? parseInt($k.val(), 10) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // Check rowcnt == 0 => Inline-Add
	var gridinsert = $fobj.find("#a_list").val() == "gridinsert";
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = ($k[0]) ? String(i) : "";
		$fobj.data("rowindex", infix);
			elm = this.GetElements("x" + infix + "_program_choice");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $application->program_choice->FldCaption(), $application->program_choice->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_full_name");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $application->full_name->FldCaption(), $application->full_name->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_secondary_School");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $application->secondary_School->FldCaption(), $application->secondary_School->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_graduation_year");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $application->graduation_year->FldCaption(), $application->graduation_year->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_graduation_year");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($application->graduation_year->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_index_number");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $application->index_number->FldCaption(), $application->index_number->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_ss_course");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $application->ss_course->FldCaption(), $application->ss_course->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_aggregate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $application->aggregate->FldCaption(), $application->aggregate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_aggregate");
			if (elm && !ew_CheckInteger(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($application->aggregate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_certificate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $application->certificate->FldCaption(), $application->certificate->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_upload_certificate");
			elm = this.GetElements("fn_x" + infix + "_upload_certificate");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $application->upload_certificate->FldCaption(), $application->upload_certificate->ReqErrMsg)) ?>");

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}

	// Process detail forms
	var dfs = $fobj.find("input[name='detailpage']").get();
	for (var i = 0; i < dfs.length; i++) {
		var df = dfs[i], val = df.value;
		if (val && ewForms[val])
			if (!ewForms[val].Validate())
				return false;
	}
	return true;
}

// Form_CustomValidate event
fapplicationadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fapplicationadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fapplicationadd.Lists["x_program_choice"] = {"LinkField":"x_program","Ajax":true,"AutoFill":false,"DisplayFields":["x_program","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"programs"};
fapplicationadd.Lists["x_program_choice"].Data = "<?php echo $application_add->program_choice->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $application_add->ShowPageHeader(); ?>
<?php
$application_add->ShowMessage();
?>
<form name="fapplicationadd" id="fapplicationadd" class="<?php echo $application_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($application_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $application_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="application">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($application_add->IsModal) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($application->program_choice->Visible) { // program_choice ?>
	<div id="r_program_choice" class="form-group">
		<label id="elh_application_program_choice" for="x_program_choice" class="<?php echo $application_add->LeftColumnClass ?>"><?php echo $application->program_choice->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $application_add->RightColumnClass ?>"><div<?php echo $application->program_choice->CellAttributes() ?>>
<span id="el_application_program_choice">
<select data-table="application" data-field="x_program_choice" data-value-separator="<?php echo $application->program_choice->DisplayValueSeparatorAttribute() ?>" id="x_program_choice" name="x_program_choice"<?php echo $application->program_choice->EditAttributes() ?>>
<?php echo $application->program_choice->SelectOptionListHtml("x_program_choice") ?>
</select>
</span>
<?php echo $application->program_choice->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($application->full_name->Visible) { // full name ?>
	<div id="r_full_name" class="form-group">
		<label id="elh_application_full_name" for="x_full_name" class="<?php echo $application_add->LeftColumnClass ?>"><?php echo $application->full_name->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $application_add->RightColumnClass ?>"><div<?php echo $application->full_name->CellAttributes() ?>>
<span id="el_application_full_name">
<input type="text" data-table="application" data-field="x_full_name" name="x_full_name" id="x_full_name" size="30" maxlength="70" placeholder="<?php echo ew_HtmlEncode($application->full_name->getPlaceHolder()) ?>" value="<?php echo $application->full_name->EditValue ?>"<?php echo $application->full_name->EditAttributes() ?>>
</span>
<?php echo $application->full_name->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($application->secondary_School->Visible) { // secondary_School ?>
	<div id="r_secondary_School" class="form-group">
		<label id="elh_application_secondary_School" for="x_secondary_School" class="<?php echo $application_add->LeftColumnClass ?>"><?php echo $application->secondary_School->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $application_add->RightColumnClass ?>"><div<?php echo $application->secondary_School->CellAttributes() ?>>
<span id="el_application_secondary_School">
<input type="text" data-table="application" data-field="x_secondary_School" name="x_secondary_School" id="x_secondary_School" size="30" maxlength="40" placeholder="<?php echo ew_HtmlEncode($application->secondary_School->getPlaceHolder()) ?>" value="<?php echo $application->secondary_School->EditValue ?>"<?php echo $application->secondary_School->EditAttributes() ?>>
</span>
<?php echo $application->secondary_School->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($application->graduation_year->Visible) { // graduation_year ?>
	<div id="r_graduation_year" class="form-group">
		<label id="elh_application_graduation_year" for="x_graduation_year" class="<?php echo $application_add->LeftColumnClass ?>"><?php echo $application->graduation_year->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $application_add->RightColumnClass ?>"><div<?php echo $application->graduation_year->CellAttributes() ?>>
<span id="el_application_graduation_year">
<input type="text" data-table="application" data-field="x_graduation_year" name="x_graduation_year" id="x_graduation_year" placeholder="<?php echo ew_HtmlEncode($application->graduation_year->getPlaceHolder()) ?>" value="<?php echo $application->graduation_year->EditValue ?>"<?php echo $application->graduation_year->EditAttributes() ?>>
</span>
<?php echo $application->graduation_year->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($application->index_number->Visible) { // index_number ?>
	<div id="r_index_number" class="form-group">
		<label id="elh_application_index_number" for="x_index_number" class="<?php echo $application_add->LeftColumnClass ?>"><?php echo $application->index_number->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $application_add->RightColumnClass ?>"><div<?php echo $application->index_number->CellAttributes() ?>>
<span id="el_application_index_number">
<input type="text" data-table="application" data-field="x_index_number" name="x_index_number" id="x_index_number" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($application->index_number->getPlaceHolder()) ?>" value="<?php echo $application->index_number->EditValue ?>"<?php echo $application->index_number->EditAttributes() ?>>
</span>
<?php echo $application->index_number->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($application->ss_course->Visible) { // ss_course ?>
	<div id="r_ss_course" class="form-group">
		<label id="elh_application_ss_course" for="x_ss_course" class="<?php echo $application_add->LeftColumnClass ?>"><?php echo $application->ss_course->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $application_add->RightColumnClass ?>"><div<?php echo $application->ss_course->CellAttributes() ?>>
<span id="el_application_ss_course">
<input type="text" data-table="application" data-field="x_ss_course" name="x_ss_course" id="x_ss_course" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($application->ss_course->getPlaceHolder()) ?>" value="<?php echo $application->ss_course->EditValue ?>"<?php echo $application->ss_course->EditAttributes() ?>>
</span>
<?php echo $application->ss_course->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($application->aggregate->Visible) { // aggregate ?>
	<div id="r_aggregate" class="form-group">
		<label id="elh_application_aggregate" for="x_aggregate" class="<?php echo $application_add->LeftColumnClass ?>"><?php echo $application->aggregate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $application_add->RightColumnClass ?>"><div<?php echo $application->aggregate->CellAttributes() ?>>
<span id="el_application_aggregate">
<input type="text" data-table="application" data-field="x_aggregate" name="x_aggregate" id="x_aggregate" size="30" placeholder="<?php echo ew_HtmlEncode($application->aggregate->getPlaceHolder()) ?>" value="<?php echo $application->aggregate->EditValue ?>"<?php echo $application->aggregate->EditAttributes() ?>>
</span>
<?php echo $application->aggregate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($application->certificate->Visible) { // certificate ?>
	<div id="r_certificate" class="form-group">
		<label id="elh_application_certificate" for="x_certificate" class="<?php echo $application_add->LeftColumnClass ?>"><?php echo $application->certificate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $application_add->RightColumnClass ?>"><div<?php echo $application->certificate->CellAttributes() ?>>
<span id="el_application_certificate">
<input type="text" data-table="application" data-field="x_certificate" name="x_certificate" id="x_certificate" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($application->certificate->getPlaceHolder()) ?>" value="<?php echo $application->certificate->EditValue ?>"<?php echo $application->certificate->EditAttributes() ?>>
</span>
<?php echo $application->certificate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($application->upload_certificate->Visible) { // upload_certificate ?>
	<div id="r_upload_certificate" class="form-group">
		<label id="elh_application_upload_certificate" class="<?php echo $application_add->LeftColumnClass ?>"><?php echo $application->upload_certificate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $application_add->RightColumnClass ?>"><div<?php echo $application->upload_certificate->CellAttributes() ?>>
<span id="el_application_upload_certificate">
<div id="fd_x_upload_certificate">
<span title="<?php echo $application->upload_certificate->FldTitle() ? $application->upload_certificate->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($application->upload_certificate->ReadOnly || $application->upload_certificate->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="application" data-field="x_upload_certificate" name="x_upload_certificate" id="x_upload_certificate"<?php echo $application->upload_certificate->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_upload_certificate" id= "fn_x_upload_certificate" value="<?php echo $application->upload_certificate->Upload->FileName ?>">
<input type="hidden" name="fa_x_upload_certificate" id= "fa_x_upload_certificate" value="0">
<input type="hidden" name="fs_x_upload_certificate" id= "fs_x_upload_certificate" value="200">
<input type="hidden" name="fx_x_upload_certificate" id= "fx_x_upload_certificate" value="<?php echo $application->upload_certificate->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_upload_certificate" id= "fm_x_upload_certificate" value="<?php echo $application->upload_certificate->UploadMaxFileSize ?>">
</div>
<table id="ft_x_upload_certificate" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $application->upload_certificate->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$application_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $application_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $application_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fapplicationadd.Init();
</script>
<?php
$application_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$application_add->Page_Terminate();
?>
