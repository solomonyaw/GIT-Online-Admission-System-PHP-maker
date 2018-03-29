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

$register = NULL; // Initialize page object first

class cregister extends cstudents {

	// Page ID
	var $PageID = 'register';

	// Project ID
	var $ProjectID = '{F31DB578-461D-4551-B52B-112914F68329}';

	// Page object name
	var $PageObjName = 'register';

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
		return "";
	}

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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
		return TRUE;
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
		if (!isset($GLOBALS["students"])) $GLOBALS["students"] = new cstudents();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'register', TRUE);

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

		// NOTE: Security object may be needed in other part of the script, skip set to Nothing
		// 
		// Security = null;
		// 
		// Create form object

		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"]; // Set up current action

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
	var $FormClassName = "form-horizontal ewForm ewRegisterForm";

	// CAPTCHA
	var $Captcha;

	// Reset Captcha
	function ResetCaptcha() {
		$_SESSION["EW_CAPTCHA_CODE"] = ew_Random();
	}

	// Validate Captcha
	function ValidateCaptcha() {
		return ($this->Captcha == @$_SESSION["EW_CAPTCHA_CODE"]);
	}

	//
	// Page main
	//
	function Page_Main() {
		global $UserTableConn, $Security, $Language, $gsLanguage, $gsFormError, $objForm;
		global $Breadcrumb;
		$this->FormClassName = "ewForm ewRegisterForm form-horizontal";

		// Set up Breadcrumb
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$Breadcrumb = new cBreadcrumb();
		$Breadcrumb->Add("register", "RegisterPage", $url, "", "", TRUE);
		$this->Heading = $Language->Phrase("RegisterPage");
		$bUserExists = FALSE;
		$this->LoadRowValues(); // Load default values
		if (@$_POST["a_register"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_register"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->CurrentAction = "I"; // Display blank record
		}

		// CAPTCHA checking
		if ($this->CurrentAction == "I" || $this->CurrentAction == "C") {
			$this->ResetCaptcha();
		} elseif (ew_IsPost()) {
			$objForm->Index = -1;
			$this->Captcha = $objForm->GetValue("captcha");
			if (!$this->ValidateCaptcha()) { // CAPTCHA unmatched
				$this->setFailureMessage($Language->Phrase("EnterValidateCode"));
				$this->CurrentAction = "I"; // Reset action, do not insert
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
			} else {
				if ($this->CurrentAction == "A")
					$this->ResetCaptcha();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add

				// Check for duplicate User ID
				$sFilter = str_replace("%u", ew_AdjustSql($this->Username->CurrentValue, EW_USER_TABLE_DBID), EW_USER_NAME_FILTER);

				// Set up filter (SQL WHERE clause) and get return SQL
				// SQL constructor in students class, studentsinfo.php

				$this->CurrentFilter = $sFilter;
				$sUserSql = $this->SQL();
				if ($rs = $UserTableConn->Execute($sUserSql)) {
					if (!$rs->EOF) {
						$bUserExists = TRUE;
						$this->RestoreFormValues(); // Restore form values
						$this->setFailureMessage($Language->Phrase("UserExists")); // Set user exist message
					}
					$rs->Close();
				}
				if (!$bUserExists) {
					$this->SendEmail = TRUE; // Send email on add success
					if ($this->AddRow()) { // Add record
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("RegisterSuccess")); // Register success

						// Auto login user
						if ($Security->ValidateUser($this->Username->CurrentValue, $this->Password->FormValue, TRUE)) {

							// Nothing to do
						} else {
							$this->setFailureMessage($Language->Phrase("AutoLoginFailed")); // Set auto login failed message
						}
						$this->Page_Terminate("index.php"); // Return
					} else {
						$this->RestoreFormValues(); // Restore form values
					}
				}
		}

		// Render row
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view
		} else {
			$this->RowType = EW_ROWTYPE_ADD; // Render add
		}
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
		$this->Password->ConfirmValue = $objForm->GetValue("c_Password");
		if (!$this->Country->FldIsDetailKey) {
			$this->Country->setFormValue($objForm->GetValue("x_Country"));
		}
		if (!$this->_Email->FldIsDetailKey) {
			$this->_Email->setFormValue($objForm->GetValue("x__Email"));
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
			if ($this->CurrentAction == "I" && !$this->EventCancelled) ew_RenderUploadField($this->photo);

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
			ew_AddMessage($gsFormError, $Language->Phrase("EnterUserName"));
		}
		if (!$this->Password->FldIsDetailKey && !is_null($this->Password->FormValue) && $this->Password->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterPassword"));
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

			// Call User Registered event
			$this->User_Registered($rsnew);
		}

		// photo
		ew_CleanUploadTempPath($this->photo, $this->photo->Upload->Index);
		return $AddRow;
	}

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

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

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// User Registered event
	function User_Registered(&$rs) {

		//echo "User_Registered";
	}

	// User Activated event
	function User_Activated(&$rs) {

		//echo "User_Activated";
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($register)) $register = new cregister();

// Page init
$register->Page_Init();

// Page main
$register->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$register->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "register";
var CurrentForm = fregister = new ew_Form("fregister", "register");

// Validate form
fregister.Validate = function() {
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
				return this.OnError(elm, ewLanguage.Phrase("EnterUserName"));
			elm = this.GetElements("x" + infix + "_Password");
			if (elm && !ew_IsHidden(elm) && !ew_HasValue(elm))
				return this.OnError(elm, ewLanguage.Phrase("EnterPassword"));
			if (fobj.c_Password.value != fobj.x_Password.value)
				return this.OnError(fobj.c_Password, ewLanguage.Phrase("MismatchPassword"));
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

			// Fire Form_CustomValidate event
			if (!this.Form_CustomValidate(fobj))
				return false;
	}
		if (fobj.captcha && !ew_HasValue(fobj.captcha))
			return this.OnError(fobj.captcha, ewLanguage.Phrase("EnterValidateCode"));
	return true;
}

// Form_CustomValidate event
fregister.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fregister.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php $register->ShowPageHeader(); ?>
<?php
$register->ShowMessage();
?>
<form name="fregister" id="fregister" class="<?php echo $register->FormClassName ?>" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($register->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $register->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="students">
<input type="hidden" name="a_register" id="a_register" value="A">
<!-- Fields to prevent google autofill -->
<input class="hidden" type="text" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<input class="hidden" type="password" name="<?php echo ew_Encrypt(ew_Random()) ?>">
<?php if ($students->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F">
<?php } ?>
<div class="ewRegisterDiv"><!-- page* -->
<?php if ($students->FirstName->Visible) { // FirstName ?>
	<div id="r_FirstName" class="form-group">
		<label id="elh_students_FirstName" for="x_FirstName" class="<?php echo $register->LeftColumnClass ?>"><?php echo $students->FirstName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->FirstName->CellAttributes() ?>>
<?php if ($students->CurrentAction <> "F") { ?>
<span id="el_students_FirstName">
<input type="text" data-table="students" data-field="x_FirstName" name="x_FirstName" id="x_FirstName" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($students->FirstName->getPlaceHolder()) ?>" value="<?php echo $students->FirstName->EditValue ?>"<?php echo $students->FirstName->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_students_FirstName">
<span<?php echo $students->FirstName->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->FirstName->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x_FirstName" name="x_FirstName" id="x_FirstName" value="<?php echo ew_HtmlEncode($students->FirstName->FormValue) ?>">
<?php } ?>
<?php echo $students->FirstName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->LastName->Visible) { // LastName ?>
	<div id="r_LastName" class="form-group">
		<label id="elh_students_LastName" for="x_LastName" class="<?php echo $register->LeftColumnClass ?>"><?php echo $students->LastName->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->LastName->CellAttributes() ?>>
<?php if ($students->CurrentAction <> "F") { ?>
<span id="el_students_LastName">
<input type="text" data-table="students" data-field="x_LastName" name="x_LastName" id="x_LastName" size="30" maxlength="30" placeholder="<?php echo ew_HtmlEncode($students->LastName->getPlaceHolder()) ?>" value="<?php echo $students->LastName->EditValue ?>"<?php echo $students->LastName->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_students_LastName">
<span<?php echo $students->LastName->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->LastName->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x_LastName" name="x_LastName" id="x_LastName" value="<?php echo ew_HtmlEncode($students->LastName->FormValue) ?>">
<?php } ?>
<?php echo $students->LastName->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->BithDate->Visible) { // BithDate ?>
	<div id="r_BithDate" class="form-group">
		<label id="elh_students_BithDate" for="x_BithDate" class="<?php echo $register->LeftColumnClass ?>"><?php echo $students->BithDate->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->BithDate->CellAttributes() ?>>
<?php if ($students->CurrentAction <> "F") { ?>
<span id="el_students_BithDate">
<input type="text" data-table="students" data-field="x_BithDate" data-format="2" name="x_BithDate" id="x_BithDate" placeholder="<?php echo ew_HtmlEncode($students->BithDate->getPlaceHolder()) ?>" value="<?php echo $students->BithDate->EditValue ?>"<?php echo $students->BithDate->EditAttributes() ?>>
<?php if (!$students->BithDate->ReadOnly && !$students->BithDate->Disabled && !isset($students->BithDate->EditAttrs["readonly"]) && !isset($students->BithDate->EditAttrs["disabled"])) { ?>
<script type="text/javascript">
ew_CreateDateTimePicker("fregister", "x_BithDate", {"ignoreReadonly":true,"useCurrent":false,"format":2});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el_students_BithDate">
<span<?php echo $students->BithDate->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->BithDate->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x_BithDate" name="x_BithDate" id="x_BithDate" value="<?php echo ew_HtmlEncode($students->BithDate->FormValue) ?>">
<?php } ?>
<?php echo $students->BithDate->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->Address->Visible) { // Address ?>
	<div id="r_Address" class="form-group">
		<label id="elh_students_Address" for="x_Address" class="<?php echo $register->LeftColumnClass ?>"><?php echo $students->Address->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->Address->CellAttributes() ?>>
<?php if ($students->CurrentAction <> "F") { ?>
<span id="el_students_Address">
<input type="text" data-table="students" data-field="x_Address" name="x_Address" id="x_Address" size="30" maxlength="60" placeholder="<?php echo ew_HtmlEncode($students->Address->getPlaceHolder()) ?>" value="<?php echo $students->Address->EditValue ?>"<?php echo $students->Address->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_students_Address">
<span<?php echo $students->Address->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->Address->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x_Address" name="x_Address" id="x_Address" value="<?php echo ew_HtmlEncode($students->Address->FormValue) ?>">
<?php } ?>
<?php echo $students->Address->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->Username->Visible) { // Username ?>
	<div id="r_Username" class="form-group">
		<label id="elh_students_Username" for="x_Username" class="<?php echo $register->LeftColumnClass ?>"><?php echo $students->Username->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->Username->CellAttributes() ?>>
<?php if ($students->CurrentAction <> "F") { ?>
<span id="el_students_Username">
<input type="text" data-table="students" data-field="x_Username" name="x_Username" id="x_Username" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($students->Username->getPlaceHolder()) ?>" value="<?php echo $students->Username->EditValue ?>"<?php echo $students->Username->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_students_Username">
<span<?php echo $students->Username->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->Username->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x_Username" name="x_Username" id="x_Username" value="<?php echo ew_HtmlEncode($students->Username->FormValue) ?>">
<?php } ?>
<?php echo $students->Username->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->Password->Visible) { // Password ?>
	<div id="r_Password" class="form-group">
		<label id="elh_students_Password" for="x_Password" class="<?php echo $register->LeftColumnClass ?>"><?php echo $students->Password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->Password->CellAttributes() ?>>
<?php if ($students->CurrentAction <> "F") { ?>
<span id="el_students_Password">
<input type="text" data-table="students" data-field="x_Password" name="x_Password" id="x_Password" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($students->Password->getPlaceHolder()) ?>" value="<?php echo $students->Password->EditValue ?>"<?php echo $students->Password->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_students_Password">
<span<?php echo $students->Password->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->Password->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x_Password" name="x_Password" id="x_Password" value="<?php echo ew_HtmlEncode($students->Password->FormValue) ?>">
<?php } ?>
<?php echo $students->Password->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->Password->Visible) { // Password ?>
	<div id="r_c_Password" class="form-group">
		<label id="elh_c_students_Password" for="c_Password" class="<?php echo $register->LeftColumnClass ?>"><?php echo $Language->Phrase("Confirm") ?> <?php echo $students->Password->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->Password->CellAttributes() ?>>
<?php if ($students->CurrentAction <> "F") { ?>
<span id="el_c_students_Password">
<input type="text" data-table="students" data-field="c_Password" name="c_Password" id="c_Password" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($students->Password->getPlaceHolder()) ?>" value="<?php echo $students->Password->EditValue ?>"<?php echo $students->Password->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_c_students_Password">
<span<?php echo $students->Password->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->Password->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="c_Password" name="c_Password" id="c_Password" value="<?php echo ew_HtmlEncode($students->Password->FormValue) ?>">
<?php } ?>
</div></div>
	</div>
<?php } ?>
<?php if ($students->Country->Visible) { // Country ?>
	<div id="r_Country" class="form-group">
		<label id="elh_students_Country" for="x_Country" class="<?php echo $register->LeftColumnClass ?>"><?php echo $students->Country->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->Country->CellAttributes() ?>>
<?php if ($students->CurrentAction <> "F") { ?>
<span id="el_students_Country">
<input type="text" data-table="students" data-field="x_Country" name="x_Country" id="x_Country" size="30" maxlength="15" placeholder="<?php echo ew_HtmlEncode($students->Country->getPlaceHolder()) ?>" value="<?php echo $students->Country->EditValue ?>"<?php echo $students->Country->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_students_Country">
<span<?php echo $students->Country->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->Country->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x_Country" name="x_Country" id="x_Country" value="<?php echo ew_HtmlEncode($students->Country->FormValue) ?>">
<?php } ?>
<?php echo $students->Country->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->_Email->Visible) { // Email ?>
	<div id="r__Email" class="form-group">
		<label id="elh_students__Email" for="x__Email" class="<?php echo $register->LeftColumnClass ?>"><?php echo $students->_Email->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->_Email->CellAttributes() ?>>
<?php if ($students->CurrentAction <> "F") { ?>
<span id="el_students__Email">
<input type="text" data-table="students" data-field="x__Email" name="x__Email" id="x__Email" size="30" maxlength="50" placeholder="<?php echo ew_HtmlEncode($students->_Email->getPlaceHolder()) ?>" value="<?php echo $students->_Email->EditValue ?>"<?php echo $students->_Email->EditAttributes() ?>>
</span>
<?php } else { ?>
<span id="el_students__Email">
<span<?php echo $students->_Email->ViewAttributes() ?>>
<p class="form-control-static"><?php echo $students->_Email->ViewValue ?></p></span>
</span>
<input type="hidden" data-table="students" data-field="x__Email" name="x__Email" id="x__Email" value="<?php echo ew_HtmlEncode($students->_Email->FormValue) ?>">
<?php } ?>
<?php echo $students->_Email->CustomMsg ?></div></div>
	</div>
<?php } ?>
<?php if ($students->photo->Visible) { // photo ?>
	<div id="r_photo" class="form-group">
		<label id="elh_students_photo" class="<?php echo $register->LeftColumnClass ?>"><?php echo $students->photo->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></label>
		<div class="<?php echo $register->RightColumnClass ?>"><div<?php echo $students->photo->CellAttributes() ?>>
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
</div><!-- /page* -->
<?php if ($students->CurrentAction <> "F") { ?>
<!-- captcha html (begin) -->
<div class="form-group">
	<div class="ewCaptchaDiv col-sm-10 col-sm-offset-2">
	<p><img src="ewcaptcha.php" alt="" class="ewCaptcha"></p>
	<input type="text" name="captcha" id="captcha" class="form-control ewControl" size="30" placeholder="<?php echo $Language->Phrase("EnterValidateCode") ?>">
	</div>
</div>
<?php } else { ?>
<input type="hidden" name="captcha" id="captcha" value="<?php echo $register->Captcha ?>">
<?php } ?>
<!-- captcha html (end) -->
<div class="form-group"><!-- buttons .form-group -->
	<div class="<?php echo $register->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if ($students->CurrentAction <> "F") { // Confirm page ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit" onclick="this.form.a_register.value='F';"><?php echo $Language->Phrase("RegisterBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ewButton" name="btnAction" id="btnAction" type="submit"><?php echo $Language->Phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ewButton" name="btnCancel" id="btnCancel" type="submit" onclick="this.form.a_register.value='X';"><?php echo $Language->Phrase("CancelBtn") ?></button>
<?php } ?>
	</div><!-- /buttons offset -->
</div><!-- /buttons .form-group -->
</form>
<script type="text/javascript">
fregister.Init();
</script>
<?php
$register->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$register->Page_Terminate();
?>
