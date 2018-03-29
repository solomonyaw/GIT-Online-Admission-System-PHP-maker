<?php
if (session_id() == "") session_start(); // Init session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg14.php" ?>
<?php include_once ((EW_USE_ADODB) ? "adodb5/adodb.inc.php" : "ewmysql14.php") ?>
<?php include_once "phpfn14.php" ?>
<?php include_once "studentsinfo.php" ?>
<?php include_once "userfn14.php" ?>
<?php

//
// Page class
//

$students_add = NULL; // Initialize page object first

class cstudents_add extends cstudents {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = '{F31DB578-461D-4551-B52B-112914F68329}';

	// Table name
	var $TableName = 'students';

	// Page object name
	var $PageObjName = 'students_add';

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

		// Table object (students)
		if (!isset($GLOBALS["students"]) || get_class($GLOBALS["students"]) == "cstudents") {
			$GLOBALS["students"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["students"];
		}

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'students', TRUE);

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
				$this->Page_Terminate(ew_GetUrl("studentslist.php"));
			else
				$this->Page_Terminate(ew_GetUrl("login.php"));
		}
		if ($Security->IsLoggedIn()) {
			$Security->UserID_Loading();
			$Security->LoadUserID();
			$Security->UserID_Loaded();
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate(ew_GetUrl("studentslist.php"));
			}
		}

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action
		$this->FirstName->SetVisibility();
		$this->LastName->SetVisibility();
		$this->BithDate->SetVisibility();
		$this->Address->SetVisibility();
		$this->Username->SetVisibility();
		$this->Password->SetVisibility();
		$this->Country->SetVisibility();
		$this->_Email->SetVisibility();
		$this->photo->SetVisibility();
		$this->userlevel_id->SetVisibility();

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
		global $EW_EXPORT, $students;
		if ($this->CustomExport <> "" && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, $EW_EXPORT)) {
				$sContent = ob_get_contents();
			if ($gsExportFile == "") $gsExportFile = $this->TableVar;
			$class = $EW_EXPORT[$this->CustomExport];
			if (class_exists($class)) {
				$doc = new $class($students);
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
					if ($pageName == "studentsview.php")
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
			if (@$_GET["StudentID"] != "") {
				$this->StudentID->setQueryStringValue($_GET["StudentID"]);
				$this->setKey("StudentID", $this->StudentID->CurrentValue); // Set up key
			} else {
				$this->setKey("StudentID", ""); // Clear key
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
					$this->Page_Terminate("studentslist.php"); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "studentslist.php")
						$sReturnUrl = $this->AddMasterUrl($sReturnUrl); // List page, return to List page with correct master key if necessary
					elseif (ew_GetPageName($sReturnUrl) == "studentsview.php")
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
		$this->photo->Upload->Index = $objForm->Index;
		$this->photo->Upload->UploadFile();
		$this->photo->CurrentValue = $this->photo->Upload->FileName;
		$this->photo->CurrentValue = $this->photo->Upload->ContentType;
	}

	// Load default values
	function LoadDefaultValues() {
		$this->StudentID->CurrentValue = NULL;
		$this->StudentID->OldValue = $this->StudentID->CurrentValue;
		$this->FirstName->CurrentValue = NULL;
		$this->FirstName->OldValue = $this->FirstName->CurrentValue;
		$this->LastName->CurrentValue = NULL;
		$this->LastName->OldValue = $this->LastName->CurrentValue;
		$this->BithDate->CurrentValue = NULL;
		$this->BithDate->OldValue = $this->BithDate->CurrentValue;
		$this->Address->CurrentValue = NULL;
		$this->Address->OldValue = $this->Address->CurrentValue;
		$this->Username->CurrentValue = NULL;
		$this->Username->OldValue = $this->Username->CurrentValue;
		$this->Password->CurrentValue = NULL;
		$this->Password->OldValue = $this->Password->CurrentValue;
		$this->Country->CurrentValue = NULL;
		$this->Country->OldValue = $this->Country->CurrentValue;
		$this->_Email->CurrentValue = NULL;
		$this->_Email->OldValue = $this->_Email->CurrentValue;
		$this->photo->Upload->DbValue = NULL;
		$this->photo->OldValue = $this->photo->Upload->DbValue;
		$this->photo->CurrentValue = NULL; // Clear file related field
		$this->userlevel_id->CurrentValue = NULL;
		$this->userlevel_id->OldValue = $this->userlevel_id->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		$this->GetUploadFiles(); // Get upload files
		if (!$this->FirstName->FldIsDetailKey) {
			$this->FirstName->setFormValue($objForm->GetValue("x_FirstName"));
		}
		if (!$this->LastName->FldIsDetailKey) {
			$this->LastName->setFormValue($objForm->GetValue("x_LastName"));
		}
		if (!$this->BithDate->FldIsDetailKey) {
			$this->BithDate->setFormValue($objForm->GetValue("x_BithDate"));
			$this->BithDate->CurrentValue = ew_UnFormatDateTime($this->BithDate->CurrentValue, 2);
		}
		if (!$this->Address->FldIsDetailKey) {
			$this->Address->setFormValue($objForm->GetValue("x_Address"));
		}
		if (!$this->Username->FldIsDetailKey) {
			$this->Username->setFormValue($objForm->GetValue("x_Username"));
		}
		if (!$this->Password->FldIsDetailKey) {
			$this->Password->setFormValue($objForm->GetValue("x_Password"));
		}
		if (!$this->Country->FldIsDetailKey) {
			$this->Country->setFormValue($objForm->GetValue("x_Country"));
		}
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($objForm->GetValue("x__Email"));
		}
		if (!$this->userlevel_id->FldIsDetailKey) {
			$this->userlevel_id->setFormValue($objForm->GetValue("x_userlevel_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->FirstName->CurrentValue = $this->FirstName->FormValue;
		$this->LastName->CurrentValue = $this->LastName->FormValue;
		$this->BithDate->CurrentValue = $this->BithDate->FormValue;
		$this->BithDate->CurrentValue = ew_UnFormatDateTime($this->BithDate->CurrentValue, 2);
		$this->Address->CurrentValue = $this->Address->FormValue;
		$this->Username->CurrentValue = $this->Username->FormValue;
		$this->Password->CurrentValue = $this->Password->FormValue;
		$this->Country->CurrentValue = $this->Country->FormValue;
		$this->_Email->CurrentValue = $this->_Email->FormValue;
		$this->userlevel_id->CurrentValue = $this->userlevel_id->FormValue;
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

		// Check if valid user id
		if ($res) {
			$res = $this->ShowOptionLink('add');
			if (!$res) {
				$sUserIdMsg = ew_DeniedMsg();
				$this->setFailureMessage($sUserIdMsg);
			}
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
		$this->StudentID->setDbValue($row['StudentID']);
		$this->FirstName->setDbValue($row['FirstName']);
		$this->LastName->setDbValue($row['LastName']);
		$this->BithDate->setDbValue($row['BithDate']);
		$this->Address->setDbValue($row['Address']);
		$this->Username->setDbValue($row['Username']);
		$this->Password->setDbValue($row['Password']);
		$this->Country->setDbValue($row['Country']);
		$this->_Email->setDbValue($row['Email']);
		$this->photo->Upload->DbValue = $row['photo'];
		$this->photo->setDbValue($this->photo->Upload->DbValue);
		$this->userlevel_id->setDbValue($row['userlevel_id']);
	}

	// Return a row with default values
	function NewRow() {
		$this->LoadDefaultValues();
		$row = array();
		$row['StudentID'] = $this->StudentID->CurrentValue;
		$row['FirstName'] = $this->FirstName->CurrentValue;
		$row['LastName'] = $this->LastName->CurrentValue;
		$row['BithDate'] = $this->BithDate->CurrentValue;
		$row['Address'] = $this->Address->CurrentValue;
		$row['Username'] = $this->Username->CurrentValue;
		$row['Password'] = $this->Password->CurrentValue;
		$row['Country'] = $this->Country->CurrentValue;
		$row['Email'] = $this->_Email->CurrentValue;
		$row['photo'] = $this->photo->Upload->DbValue;
		$row['userlevel_id'] = $this->userlevel_id->CurrentValue;
		return $row;
	}

	// Load DbValue from recordset
	function LoadDbValues(&$rs) {
		if (!$rs || !is_array($rs) && $rs->EOF)
			return;
		$row = is_array($rs) ? $rs : $rs->fields;
		$this->StudentID->DbValue = $row['StudentID'];
		$this->FirstName->DbValue = $row['FirstName'];
		$this->LastName->DbValue = $row['LastName'];
		$this->BithDate->DbValue = $row['BithDate'];
		$this->Address->DbValue = $row['Address'];
		$this->Username->DbValue = $row['Username'];
		$this->Password->DbValue = $row['Password'];
		$this->Country->DbValue = $row['Country'];
		$this->_Email->DbValue = $row['Email'];
		$this->photo->Upload->DbValue = $row['photo'];
		$this->userlevel_id->DbValue = $row['userlevel_id'];
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("StudentID")) <> "")
			$this->StudentID->CurrentValue = $this->getKey("StudentID"); // StudentID
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

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

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
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// FirstName
			$this->FirstName->EditAttrs["class"] = "form-control";
			$this->FirstName->EditCustomAttributes = "";
			$this->FirstName->EditValue = ew_HtmlEncode($this->FirstName->CurrentValue);
			$this->FirstName->PlaceHolder = ew_RemoveHtml($this->FirstName->FldCaption());

			// LastName
			$this->LastName->EditAttrs["class"] = "form-control";
			$this->LastName->EditCustomAttributes = "";
			$this->LastName->EditValue = ew_HtmlEncode($this->LastName->CurrentValue);
			$this->LastName->PlaceHolder = ew_RemoveHtml($this->LastName->FldCaption());

			// BithDate
			$this->BithDate->EditAttrs["class"] = "form-control";
			$this->BithDate->EditCustomAttributes = "";
			$this->BithDate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->BithDate->CurrentValue, 2));
			$this->BithDate->PlaceHolder = ew_RemoveHtml($this->BithDate->FldCaption());

			// Address
			$this->Address->EditAttrs["class"] = "form-control";
			$this->Address->EditCustomAttributes = "";
			$this->Address->EditValue = ew_HtmlEncode($this->Address->CurrentValue);
			$this->Address->PlaceHolder = ew_RemoveHtml($this->Address->FldCaption());

			// Username
			$this->Username->EditAttrs["class"] = "form-control";
			$this->Username->EditCustomAttributes = "";
			$this->Username->EditValue = ew_HtmlEncode($this->Username->CurrentValue);
			$this->Username->PlaceHolder = ew_RemoveHtml($this->Username->FldCaption());

			// Password
			$this->Password->EditAttrs["class"] = "form-control";
			$this->Password->EditCustomAttributes = "";
			$this->Password->EditValue = ew_HtmlEncode($this->Password->CurrentValue);
			$this->Password->PlaceHolder = ew_RemoveHtml($this->Password->FldCaption());

			// Country
			$this->Country->EditAttrs["class"] = "form-control";
			$this->Country->EditCustomAttributes = "";
			$this->Country->EditValue = ew_HtmlEncode($this->Country->CurrentValue);
			$this->Country->PlaceHolder = ew_RemoveHtml($this->Country->FldCaption());

			// Email
			$this->_Email->EditAttrs["class"] = "form-control";
			$this->_Email->EditCustomAttributes = "";
			$this->_Email->EditValue = ew_HtmlEncode($this->_Email->CurrentValue);
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
			if (($this->CurrentAction == "I" || $this->CurrentAction == "C") && !$this->EventCancelled) ew_RenderUploadField($this->photo);

			// userlevel_id
			$this->userlevel_id->EditAttrs["class"] = "form-control";
			$this->userlevel_id->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->userlevel_id->EditValue = $Language->Phrase("PasswordMask");
			} else {
			if (trim(strval($this->userlevel_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->userlevel_id->CurrentValue, EW_DATATYPE_NUMBER, "");
			}
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			$this->userlevel_id->LookupFilters = array();
			ew_AddFilter($sWhereWrk, $sFilterWrk);
			$this->Lookup_Selecting($this->userlevel_id, $sWhereWrk); // Call Lookup Selecting
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = Conn()->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			$this->userlevel_id->EditValue = $arwrk;
			}

			// Add refer script
			// FirstName

			$this->FirstName->LinkCustomAttributes = "";
			$this->FirstName->HrefValue = "";

			// LastName
			$this->LastName->LinkCustomAttributes = "";
			$this->LastName->HrefValue = "";

			// BithDate
			$this->BithDate->LinkCustomAttributes = "";
			$this->BithDate->HrefValue = "";

			// Address
			$this->Address->LinkCustomAttributes = "";
			$this->Address->HrefValue = "";

			// Username
			$this->Username->LinkCustomAttributes = "";
			$this->Username->HrefValue = "";

			// Password
			$this->Password->LinkCustomAttributes = "";
			$this->Password->HrefValue = "";

			// Country
			$this->Country->LinkCustomAttributes = "";
			$this->Country->HrefValue = "";

			// Email
			$this->_Email->LinkCustomAttributes = "";
			$this->_Email->HrefValue = "";

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

			// userlevel_id
			$this->userlevel_id->LinkCustomAttributes = "";
			$this->userlevel_id->HrefValue = "";
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
		if (!$this->FirstName->FldIsDetailKey && !is_null($this->FirstName->FormValue) && $this->FirstName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->FirstName->FldCaption(), $this->FirstName->ReqErrMsg));
		}
		if (!$this->LastName->FldIsDetailKey && !is_null($this->LastName->FormValue) && $this->LastName->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->LastName->FldCaption(), $this->LastName->ReqErrMsg));
		}
		if (!$this->BithDate->FldIsDetailKey && !is_null($this->BithDate->FormValue) && $this->BithDate->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->BithDate->FldCaption(), $this->BithDate->ReqErrMsg));
		}
		if (!ew_CheckDateDef($this->BithDate->FormValue)) {
			ew_AddMessage($gsFormError, $this->BithDate->FldErrMsg());
		}
		if (!$this->Address->FldIsDetailKey && !is_null($this->Address->FormValue) && $this->Address->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Address->FldCaption(), $this->Address->ReqErrMsg));
		}
		if (!$this->Username->FldIsDetailKey && !is_null($this->Username->FormValue) && $this->Username->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Username->FldCaption(), $this->Username->ReqErrMsg));
		}
		if (!$this->Password->FldIsDetailKey && !is_null($this->Password->FormValue) && $this->Password->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Password->FldCaption(), $this->Password->ReqErrMsg));
		}
		if (!$this->Country->FldIsDetailKey && !is_null($this->Country->FormValue) && $this->Country->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->Country->FldCaption(), $this->Country->ReqErrMsg));
		}
		if (!$this->_Email->FldIsDetailKey && !is_null($this->_Email->FormValue) && $this->_Email->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->_Email->FldCaption(), $this->_Email->ReqErrMsg));
		}
		if ($this->photo->Upload->FileName == "" && !$this->photo->Upload->KeepFile) {
			ew_AddMessage($gsFormError, str_replace("%s", $this->photo->FldCaption(), $this->photo->ReqErrMsg));
		}
		if (!$this->userlevel_id->FldIsDetailKey && !is_null($this->userlevel_id->FormValue) && $this->userlevel_id->FormValue == "") {
			ew_AddMessage($gsFormError, str_replace("%s", $this->userlevel_id->FldCaption(), $this->userlevel_id->ReqErrMsg));
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

		// Check if valid User ID
		$bValidUser = FALSE;
		if ($Security->CurrentUserID() <> "" && !ew_Empty($this->StudentID->CurrentValue) && !$Security->IsAdmin()) { // Non system admin
			$bValidUser = $Security->IsValidUserID($this->StudentID->CurrentValue);
			if (!$bValidUser) {
				$sUserIdMsg = str_replace("%c", CurrentUserID(), $Language->Phrase("UnAuthorizedUserID"));
				$sUserIdMsg = str_replace("%u", $this->StudentID->CurrentValue, $sUserIdMsg);
				$this->setFailureMessage($sUserIdMsg);
				return FALSE;
			}
		}
		$conn = &$this->Connection();

		// Load db values from rsold
		$this->LoadDbValues($rsold);
		if ($rsold) {
		}
		$rsnew = array();

		// FirstName
		$this->FirstName->SetDbValueDef($rsnew, $this->FirstName->CurrentValue, "", FALSE);

		// LastName
		$this->LastName->SetDbValueDef($rsnew, $this->LastName->CurrentValue, "", FALSE);

		// BithDate
		$this->BithDate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->BithDate->CurrentValue, 2), ew_CurrentDate(), FALSE);

		// Address
		$this->Address->SetDbValueDef($rsnew, $this->Address->CurrentValue, "", FALSE);

		// Username
		$this->Username->SetDbValueDef($rsnew, $this->Username->CurrentValue, "", FALSE);

		// Password
		$this->Password->SetDbValueDef($rsnew, $this->Password->CurrentValue, "", FALSE);

		// Country
		$this->Country->SetDbValueDef($rsnew, $this->Country->CurrentValue, "", FALSE);

		// Email
		$this->_Email->SetDbValueDef($rsnew, $this->_Email->CurrentValue, "", FALSE);

		// photo
		if ($this->photo->Visible && !$this->photo->Upload->KeepFile) {
			$this->photo->Upload->DbValue = ""; // No need to delete old file
			if ($this->photo->Upload->FileName == "") {
				$rsnew['photo'] = NULL;
			} else {
				$rsnew['photo'] = $this->photo->Upload->FileName;
			}
			$this->photo->ImageWidth = EW_THUMBNAIL_DEFAULT_WIDTH; // Resize width
			$this->photo->ImageHeight = EW_THUMBNAIL_DEFAULT_HEIGHT; // Resize height
			$this->photo->SetDbValueDef($rsnew, trim($this->photo->Upload->ContentType), "", FALSE);
		}

		// userlevel_id
		if ($Security->CanAdmin()) { // System admin
		$this->userlevel_id->SetDbValueDef($rsnew, $this->userlevel_id->CurrentValue, 0, FALSE);
		}

		// StudentID
		if ($this->photo->Visible && !$this->photo->Upload->KeepFile) {
			$OldFiles = ew_Empty($this->photo->Upload->DbValue) ? array() : array($this->photo->Upload->DbValue);
			if (!ew_Empty($this->photo->Upload->FileName)) {
				$NewFiles = array($this->photo->Upload->FileName);
				$NewFileCount = count($NewFiles);
				for ($i = 0; $i < $NewFileCount; $i++) {
					$fldvar = ($this->photo->Upload->Index < 0) ? $this->photo->FldVar : substr($this->photo->FldVar, 0, 1) . $this->photo->Upload->Index . substr($this->photo->FldVar, 1);
					if ($NewFiles[$i] <> "") {
						$file = $NewFiles[$i];
						if (file_exists(ew_UploadTempPath($fldvar, $this->photo->TblVar) . $file)) {
							$file1 = ew_UploadFileNameEx($this->photo->PhysicalUploadPath(), $file); // Get new file name
							if ($file1 <> $file) { // Rename temp file
								while (file_exists(ew_UploadTempPath($fldvar, $this->photo->TblVar) . $file1) || file_exists($this->photo->PhysicalUploadPath() . $file1)) // Make sure no file name clash
									$file1 = ew_UniqueFilename($this->photo->PhysicalUploadPath(), $file1, TRUE); // Use indexed name
								rename(ew_UploadTempPath($fldvar, $this->photo->TblVar) . $file, ew_UploadTempPath($fldvar, $this->photo->TblVar) . $file1);
								$NewFiles[$i] = $file1;
							}
						}
					}
				}
				$this->photo->Upload->DbValue = empty($OldFiles) ? "" : implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $OldFiles);
				$this->photo->Upload->FileName = implode(EW_MULTIPLE_UPLOAD_SEPARATOR, $NewFiles);
				$this->photo->SetDbValueDef($rsnew, $this->photo->Upload->FileName, "", FALSE);
			}
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
		if ($bInsertRow) {
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
				if ($this->photo->Visible && !$this->photo->Upload->KeepFile) {
					$OldFiles = ew_Empty($this->photo->Upload->DbValue) ? array() : array($this->photo->Upload->DbValue);
					if (!ew_Empty($this->photo->Upload->FileName)) {
						$NewFiles = array($this->photo->Upload->FileName);
						$NewFiles2 = array($rsnew['photo']);
						$NewFileCount = count($NewFiles);
						for ($i = 0; $i < $NewFileCount; $i++) {
							$fldvar = ($this->photo->Upload->Index < 0) ? $this->photo->FldVar : substr($this->photo->FldVar, 0, 1) . $this->photo->Upload->Index . substr($this->photo->FldVar, 1);
							if ($NewFiles[$i] <> "") {
								$file = ew_UploadTempPath($fldvar, $this->photo->TblVar) . $NewFiles[$i];
								if (file_exists($file)) {
									if (@$NewFiles2[$i] <> "") // Use correct file name
										$NewFiles[$i] = $NewFiles2[$i];
									if (!$this->photo->Upload->ResizeAndSaveToFile($this->photo->ImageWidth, $this->photo->ImageHeight, EW_THUMBNAIL_DEFAULT_QUALITY, $NewFiles[$i], TRUE, $i)) {
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

		// photo
		ew_CleanUploadTempPath($this->photo, $this->photo->Upload->Index);
		return $AddRow;
	}

	// Show link optionally based on User ID
	function ShowOptionLink($id = "") {
		global $Security;
		if ($Security->IsLoggedIn() && !$Security->IsAdmin() && !$this->UserIDAllow($id))
			return $Security->IsValidUserID($this->StudentID->CurrentValue);
		return TRUE;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb->Add("list", $this->TableVar, $this->AddMasterUrl("studentslist.php"), "", $this->TableVar, TRUE);
		$PageId = ($this->CurrentAction == "C") ? "Copy" : "Add";
		$Breadcrumb->Add("add", $PageId, $url);
	}

	// Setup lookup filters of a field
	function SetupLookupFilters($fld, $pageId = null) {
		global $gsLanguage;
		$pageId = $pageId ?: $this->PageID;
		switch ($fld->FldVar) {
		case "x_userlevel_id":
			$sSqlWrk = "";
			$sSqlWrk = "SELECT `userlevelid` AS `LinkFld`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			$fld->LookupFilters = array();
			$fld->LookupFilters += array("s" => $sSqlWrk, "d" => "", "f0" => '`userlevelid` IN ({filter_value})', "t0" => "3", "fn0" => "");
			$sSqlWrk = "";
			$this->Lookup_Selecting($this->userlevel_id, $sWhereWrk); // Call Lookup Selecting
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
if (!isset($students_add)) $students_add = new cstudents_add();

// Page init
$students_add->Page_Init();

// Page main
$students_add->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$students_add->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "add";
var CurrentForm = fstudentsadd = new ew_Form("fstudentsadd", "add");

// Validate form
fstudentsadd.Validate = function() {
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
			elm = this.GetElements("x" + infix + "_FirstName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $students->FirstName->FldCaption(), $students->FirstName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_LastName");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $students->LastName->FldCaption(), $students->LastName->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_BithDate");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $students->BithDate->FldCaption(), $students->BithDate->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_BithDate");
			if (elm && !ew_CheckDateDef(elm.value))
				return this.OnError(elm, "<?php echo ew_JsEncode2($students->BithDate->FldErrMsg()) ?>");
			elm = this.GetElements("x" + infix + "_Address");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $students->Address->FldCaption(), $students->Address->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Username");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $students->Username->FldCaption(), $students->Username->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $students->Password->FldCaption(), $students->Password->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_Country");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $students->Country->FldCaption(), $students->Country->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "__Email");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $students->_Email->FldCaption(), $students->_Email->ReqErrMsg)) ?>");
			felm = this.GetElements("x" + infix + "_photo");
			elm = this.GetElements("fn_x" + infix + "_photo");
			if (felm && elm && !ew_HasValue(elm))
				return this.OnError(felm, "<?php echo ew_JsEncode2(str_replace("%s", $students->photo->FldCaption(), $students->photo->ReqErrMsg)) ?>");
			elm = this.GetElements("x" + infix + "_userlevel_id");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, "<?php echo ew_JsEncode2(str_replace("%s", $students->userlevel_id->FldCaption(), $students->userlevel_id->ReqErrMsg)) ?>");

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
fstudentsadd.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstudentsadd.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fstudentsadd.Lists["x_userlevel_id"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
fstudentsadd.Lists["x_userlevel_id"].Data = "<?php echo $students_add->userlevel_id->LookupFilterQuery(FALSE, "add") ?>";

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $students_add->ShowPageHeader(); ?>
<?php
$students_add->ShowMessage();
?>
<form name="fstudentsadd" id="fstudentsadd" class="<?php echo $students_add->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($students_add->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $students_add->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="students">
<input type="hidden" name="a_add" id="a_add" value="A">
<input type="hidden" name="modal" value="<?php echo intval($students_add->IsModal) ?>">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<div class="ewAddDiv"><!-- page* -->
<?php if ($students->FirstName->Visible) { // FirstName ?>
	<div id="r_FirstName" class="form-group">
		<label id="elh_students_FirstName" for="x_FirstName" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->FirstName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->FirstName->CellAttributes() ?>>
<span id="el_students_FirstName">
<input type="text" data-table="students" data-field="x_FirstName" name="x_FirstName" id="x_FirstName" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($students->FirstName->getPlaceHolder()) ?>" value="<?php echo $students->FirstName->EditValue ?>"<?php echo $students->FirstName->EditAttributes() ?>>
</span>
<?php echo $students->FirstName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->LastName->Visible) { // LastName ?>
	<div id="r_LastName" class="form-group">
		<label id="elh_students_LastName" for="x_LastName" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->LastName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->LastName->CellAttributes() ?>>
<span id="el_students_LastName">
<input type="text" data-table="students" data-field="x_LastName" name="x_LastName" id="x_LastName" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($students->LastName->getPlaceHolder()) ?>" value="<?php echo $students->LastName->EditValue ?>"<?php echo $students->LastName->EditAttributes() ?>>
</span>
<?php echo $students->LastName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->BithDate->Visible) { // BithDate ?>
	<div id="r_BithDate" class="form-group">
		<label id="elh_students_BithDate" for="x_BithDate" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->BithDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->BithDate->CellAttributes() ?>>
<span id="el_students_BithDate">
<input type="text" data-table="students" data-field="x_BithDate" data-format="2" name="x_BithDate" id="x_BithDate" placeholder="<?php echo ew_HtmlEncode($students->BithDate->getPlaceHolder()) ?>" value="<?php echo $students->BithDate->EditValue ?>"<?php echo $students->BithDate->EditAttributes() ?>>
<?php if (!$students->BithDate->ReadOnly && !$students->BithDate->Disabled && !isset($students->BithDate->EditAttrs["readonly"]) && !isset($students->BithDate->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fstudentsadd", "x_BithDate", {"ignoreReadonly":true,"useCurrent":false,"format":2});
</script>
<?php } ?>
</span>
<?php echo $students->BithDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->Address->Visible) { // Address ?>
	<div id="r_Address" class="form-group">
		<label id="elh_students_Address" for="x_Address" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->Address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->Address->CellAttributes() ?>>
<span id="el_students_Address">
<input type="text" data-table="students" data-field="x_Address" name="x_Address" id="x_Address" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($students->Address->getPlaceHolder()) ?>" value="<?php echo $students->Address->EditValue ?>"<?php echo $students->Address->EditAttributes() ?>>
</span>
<?php echo $students->Address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->Username->Visible) { // Username ?>
	<div id="r_Username" class="form-group">
		<label id="elh_students_Username" for="x_Username" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->Username->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->Username->CellAttributes() ?>>
<span id="el_students_Username">
<input type="text" data-table="students" data-field="x_Username" name="x_Username" id="x_Username" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($students->Username->getPlaceHolder()) ?>" value="<?php echo $students->Username->EditValue ?>"<?php echo $students->Username->EditAttributes() ?>>
</span>
<?php echo $students->Username->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->Password->Visible) { // Password ?>
	<div id="r_Password" class="form-group">
		<label id="elh_students_Password" for="x_Password" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->Password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->Password->CellAttributes() ?>>
<span id="el_students_Password">
<input type="text" data-table="students" data-field="x_Password" name="x_Password" id="x_Password" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($students->Password->getPlaceHolder()) ?>" value="<?php echo $students->Password->EditValue ?>"<?php echo $students->Password->EditAttributes() ?>>
</span>
<?php echo $students->Password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->Country->Visible) { // Country ?>
	<div id="r_Country" class="form-group">
		<label id="elh_students_Country" for="x_Country" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->Country->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->Country->CellAttributes() ?>>
<span id="el_students_Country">
<input type="text" data-table="students" data-field="x_Country" name="x_Country" id="x_Country" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($students->Country->getPlaceHolder()) ?>" value="<?php echo $students->Country->EditValue ?>"<?php echo $students->Country->EditAttributes() ?>>
</span>
<?php echo $students->Country->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label id="elh_students__Email" for="x__Email" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->_Email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->_Email->CellAttributes() ?>>
<span id="el_students__Email">
<input type="text" data-table="students" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($students->_Email->getPlaceHolder()) ?>" value="<?php echo $students->_Email->EditValue ?>"<?php echo $students->_Email->EditAttributes() ?>>
</span>
<?php echo $students->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->photo->Visible) { // photo ?>
	<div id="r_photo" class="form-group">
		<label id="elh_students_photo" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->photo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->photo->CellAttributes() ?>>
<span id="el_students_photo">
<div id="fd_x_photo">
<span title="<?php echo $students->photo->FldTitle() ? $students->photo->FldTitle() : $Language->Phrase("ChooseFile") ?>" class="btn btn-default btn-sm fileinput-button ewTooltip<?php if ($students->photo->ReadOnly || $students->photo->Disabled) echo " hide"; ?>">
	<span><?php echo $Language->Phrase("ChooseFileBtn") ?></span>
	<input type="file" title=" " data-table="students" data-field="x_photo" name="x_photo" id="x_photo"<?php echo $students->photo->EditAttributes() ?>>
</span>
<input type="hidden" name="fn_x_photo" id= "fn_x_photo" value="<?php echo $students->photo->Upload->FileName ?>">
<input type="hidden" name="fa_x_photo" id= "fa_x_photo" value="0">
<input type="hidden" name="fs_x_photo" id= "fs_x_photo" value="255">
<input type="hidden" name="fx_x_photo" id= "fx_x_photo" value="<?php echo $students->photo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_photo" id= "fm_x_photo" value="<?php echo $students->photo->UploadMaxFileSize ?>">
</div>
<table id="ft_x_photo" class="table table-condensed pull-left ewUploadTable"><tbody class="files"></tbody></table>
</span>
<?php echo $students->photo->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->userlevel_id->Visible) { // userlevel_id ?>
	<div id="r_userlevel_id" class="form-group">
		<label id="elh_students_userlevel_id" for="x_userlevel_id" class="<?php echo $students_add->LeftColumnClass ?>"><?php echo $students->userlevel_id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $students_add->RightColumnClass ?>"><div<?php echo $students->userlevel_id->CellAttributes() ?>>
<?php if (!$Security->IsAdmin() && $Security->IsLoggedIn()) { // Non system admin ?>
<span id="el_students_userlevel_id">
<p class="form-control-static"><?php echo $students->userlevel_id->EditValue ?></p>
</span>
<?php } else { ?>
<span id="el_students_userlevel_id">
<select data-table="students" data-field="x_userlevel_id" data-value-separator="<?php echo $students->userlevel_id->DisplayValueSeparatorAttribute() ?>" id="x_userlevel_id" name="x_userlevel_id"<?php echo $students->userlevel_id->EditAttributes() ?>>
<?php echo $students->userlevel_id->SelectOptionListHtml("x_userlevel_id") ?>
</select>
</span>
<?php } ?>
<?php echo $students->userlevel_id->CustomMsg ?></div></div>
	</div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$students_add->IsModal) { ?>
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $students_add->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("AddBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="button" data-href="<?php echo $students_add->getReturnUrl() ?>"><?php echo $Language->Phrase("CancelBtn") ?></button>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
<?php } ?>
</form>
<script type="text/javascript">
fstudentsadd.Init();
</script>
<?php
$students_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$students_add->Page_Terminate();
?>
