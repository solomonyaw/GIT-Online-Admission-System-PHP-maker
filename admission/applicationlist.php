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

$application_list = NULL; // Initialize page object first

class capplication_list extends capplication {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{F31DB578-461D-4551-B52B-112914F68329}';

	// Table name
	var $TableName = 'application';

	// Page object name
	var $PageObjName = 'application_list';

	// Grid form hidden field names
	var $FormName = 'fapplicationlist';
	var $FormActionName = 'k_action';
	var $FormKeyName = 'k_key';
	var $FormOldKeyName = 'k_oldkey';
	var $FormBlankRowName = 'k_blankrow';
	var $FormKeyCountName = 'key_count';

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

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Custom export
	var $ExportExcelCustom = FALSE;
	var $ExportWordCustom = FALSE;
	var $ExportPdfCustom = FALSE;
	var $ExportEmailCustom = FALSE;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "applicationadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "applicationdelete.php";
		$this->MultiUpdateUrl = "applicationupdate.php";

		// Table object (students)
		if (!isset($GLOBALS['students'])) $GLOBALS['students'] = new cstudents();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "div";
		$this->ExportOptions->TagClassName = "ewExportOption";

		// Other options
		$this->OtherOptions['addedit'] = new cListOptions();
		$this->OtherOptions['addedit']->Tag = "div";
		$this->OtherOptions['addedit']->TagClassName = "ewAddEditOption";
		$this->OtherOptions['detail'] = new cListOptions();
		$this->OtherOptions['detail']->Tag = "div";
		$this->OtherOptions['detail']->TagClassName = "ewDetailOption";
		$this->OtherOptions['action'] = new cListOptions();
		$this->OtherOptions['action']->Tag = "div";
		$this->OtherOptions['action']->TagClassName = "ewActionOption";

		// Filter options
		$this->FilterOptions = new cListOptions();
		$this->FilterOptions->Tag = "div";
		$this->FilterOptions->TagClassName = "ewFilterOption fapplicationlistsrch";

		// List actions
		$this->ListActions = new cListActions();
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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
			$this->Page_Terminate(ew_GetUrl("index.php"));
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

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

		// Setup other options
		$this->SetupOtherOptions();

		// Set up custom action (compatible with old version)
		foreach ($this->CustomActions as $name => $action)
			$this->ListActions->Add($name, $action);

		// Show checkbox column if multiple action
		foreach ($this->ListActions->Items as $listaction) {
			if ($listaction->Select == EW_ACTION_MULTIPLE && $listaction->Allow) {
				$this->ListOptions->Items["checkbox"]->Visible = TRUE;
				break;
			}
		}
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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $SearchOptions; // Search options
	var $OtherOptions = array(); // Other options
	var $FilterOptions; // Filter options
	var $ListActions; // List actions
	var $SelectedCount = 0;
	var $SelectedIndex = 0;
	var $DisplayRecs = 20;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $AutoHidePager = EW_AUTO_HIDE_PAGER;
	var $AutoHidePageSizeSelector = EW_AUTO_HIDE_PAGE_SIZE_SELECTOR;
	var $DefaultSearchWhere = ""; // Default search WHERE clause
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $MultiColumnClass;
	var $MultiColumnEditClass = "col-sm-12";
	var $MultiColumnCnt = 12;
	var $MultiColumnEditCnt = 12;
	var $GridCnt = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;
	var $MultiSelectKey;
	var $Command;
	var $RestoreSearch = FALSE;
	var $DetailPages;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security, $EW_EXPORT;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Process list action first
			if ($this->ProcessListAction()) // Ajax request
				$this->Page_Terminate();

			// Handle reset command
			$this->ResetCmd();

			// Set up Breadcrumb
			if ($this->Export == "")
				$this->SetupBreadcrumb();

			// Hide list options
			if ($this->Export <> "") {
				$this->ListOptions->HideAllOptions(array("sequence"));
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			} elseif ($this->CurrentAction == "gridadd" || $this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ListOptions->UseDropDownButton = FALSE; // Disable drop down button
				$this->ListOptions->UseButtonGroup = FALSE; // Disable button group
			}

			// Hide options
			if ($this->Export <> "" || $this->CurrentAction <> "") {
				$this->ExportOptions->HideAllOptions();
				$this->FilterOptions->HideAllOptions();
			}

			// Hide other options
			if ($this->Export <> "") {
				foreach ($this->OtherOptions as &$option)
					$option->HideAllOptions();
			}

			// Get default search criteria
			ew_AddFilter($this->DefaultSearchWhere, $this->BasicSearchWhere(TRUE));

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Process filter list
			$this->ProcessFilterList();

			// Restore search parms from Session if not searching / reset / export
			if (($this->Export <> "" || $this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall") && $this->Command <> "json" && $this->CheckSearchParms())
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetupSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->Command <> "json" && $this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 20; // Load default
		}

		// Load Sorting Order
		if ($this->Command <> "json")
			$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search" && !$this->RestoreSearch) {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif ($this->Command <> "json") {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter
		if ($this->Command == "json") {
			$this->UseSessionForListSQL = FALSE; // Do not use session for ListSQL
			$this->CurrentFilter = $sFilter;
		} else {
			$this->setSessionWhere($sFilter);
			$this->CurrentFilter = "";
		}

		// Load record count first
		if (!$this->IsAddOrEdit()) {
			$bSelectLimit = $this->UseSelectLimit;
			if ($bSelectLimit) {
				$this->TotalRecs = $this->ListRecordCount();
			} else {
				if ($this->Recordset = $this->LoadRecordset())
					$this->TotalRecs = $this->Recordset->RecordCount();
			}
		}

		// Search options
		$this->SetupSearchOptions();
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // Next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue($this->FormKeyName));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 1) {
			$this->index_number->setFormValue($arrKeyFlds[0]);
		}
		return TRUE;
	}

	// Get list of filters
	function GetFilterList() {
		global $UserProfile;

		// Initialize
		$sFilterList = "";
		$sSavedFilterList = "";

		// Load server side filters
		if (EW_SEARCH_FILTER_OPTION == "Server" && isset($UserProfile))
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fapplicationlistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->program_choice->AdvancedSearch->ToJson(), ","); // Field program_choice
		$sFilterList = ew_Concat($sFilterList, $this->full_name->AdvancedSearch->ToJson(), ","); // Field full name
		$sFilterList = ew_Concat($sFilterList, $this->secondary_School->AdvancedSearch->ToJson(), ","); // Field secondary_School
		$sFilterList = ew_Concat($sFilterList, $this->graduation_year->AdvancedSearch->ToJson(), ","); // Field graduation_year
		$sFilterList = ew_Concat($sFilterList, $this->index_number->AdvancedSearch->ToJson(), ","); // Field index_number
		$sFilterList = ew_Concat($sFilterList, $this->ss_course->AdvancedSearch->ToJson(), ","); // Field ss_course
		$sFilterList = ew_Concat($sFilterList, $this->aggregate->AdvancedSearch->ToJson(), ","); // Field aggregate
		$sFilterList = ew_Concat($sFilterList, $this->certificate->AdvancedSearch->ToJson(), ","); // Field certificate
		$sFilterList = ew_Concat($sFilterList, $this->upload_certificate->AdvancedSearch->ToJson(), ","); // Field upload_certificate
		$sFilterList = ew_Concat($sFilterList, $this->application_status->AdvancedSearch->ToJson(), ","); // Field application_status
		if ($this->BasicSearch->Keyword <> "") {
			$sWrk = "\"" . EW_TABLE_BASIC_SEARCH . "\":\"" . ew_JsEncode2($this->BasicSearch->Keyword) . "\",\"" . EW_TABLE_BASIC_SEARCH_TYPE . "\":\"" . ew_JsEncode2($this->BasicSearch->Type) . "\"";
			$sFilterList = ew_Concat($sFilterList, $sWrk, ",");
		}
		$sFilterList = preg_replace('/,$/', "", $sFilterList);

		// Return filter list in json
		if ($sFilterList <> "")
			$sFilterList = "\"data\":{" . $sFilterList . "}";
		if ($sSavedFilterList <> "") {
			if ($sFilterList <> "")
				$sFilterList .= ",";
			$sFilterList .= "\"filters\":" . $sSavedFilterList;
		}
		return ($sFilterList <> "") ? "{" . $sFilterList . "}" : "null";
	}

	// Process filter list
	function ProcessFilterList() {
		global $UserProfile;
		if (@$_POST["ajax"] == "savefilters") { // Save filter request (Ajax)
			$filters = @$_POST["filters"];
			$UserProfile->SetSearchFilters(CurrentUserName(), "fapplicationlistsrch", $filters);

			// Clean output buffer
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			echo ew_ArrayToJson(array(array("success" => TRUE))); // Success
			$this->Page_Terminate();
			exit();
		} elseif (@$_POST["cmd"] == "resetfilter") {
			$this->RestoreFilterList();
		}
	}

	// Restore list of filters
	function RestoreFilterList() {

		// Return if not reset filter
		if (@$_POST["cmd"] <> "resetfilter")
			return FALSE;
		$filter = json_decode(@$_POST["filter"], TRUE);
		$this->Command = "search";

		// Field program_choice
		$this->program_choice->AdvancedSearch->SearchValue = @$filter["x_program_choice"];
		$this->program_choice->AdvancedSearch->SearchOperator = @$filter["z_program_choice"];
		$this->program_choice->AdvancedSearch->SearchCondition = @$filter["v_program_choice"];
		$this->program_choice->AdvancedSearch->SearchValue2 = @$filter["y_program_choice"];
		$this->program_choice->AdvancedSearch->SearchOperator2 = @$filter["w_program_choice"];
		$this->program_choice->AdvancedSearch->Save();

		// Field full name
		$this->full_name->AdvancedSearch->SearchValue = @$filter["x_full_name"];
		$this->full_name->AdvancedSearch->SearchOperator = @$filter["z_full_name"];
		$this->full_name->AdvancedSearch->SearchCondition = @$filter["v_full_name"];
		$this->full_name->AdvancedSearch->SearchValue2 = @$filter["y_full_name"];
		$this->full_name->AdvancedSearch->SearchOperator2 = @$filter["w_full_name"];
		$this->full_name->AdvancedSearch->Save();

		// Field secondary_School
		$this->secondary_School->AdvancedSearch->SearchValue = @$filter["x_secondary_School"];
		$this->secondary_School->AdvancedSearch->SearchOperator = @$filter["z_secondary_School"];
		$this->secondary_School->AdvancedSearch->SearchCondition = @$filter["v_secondary_School"];
		$this->secondary_School->AdvancedSearch->SearchValue2 = @$filter["y_secondary_School"];
		$this->secondary_School->AdvancedSearch->SearchOperator2 = @$filter["w_secondary_School"];
		$this->secondary_School->AdvancedSearch->Save();

		// Field graduation_year
		$this->graduation_year->AdvancedSearch->SearchValue = @$filter["x_graduation_year"];
		$this->graduation_year->AdvancedSearch->SearchOperator = @$filter["z_graduation_year"];
		$this->graduation_year->AdvancedSearch->SearchCondition = @$filter["v_graduation_year"];
		$this->graduation_year->AdvancedSearch->SearchValue2 = @$filter["y_graduation_year"];
		$this->graduation_year->AdvancedSearch->SearchOperator2 = @$filter["w_graduation_year"];
		$this->graduation_year->AdvancedSearch->Save();

		// Field index_number
		$this->index_number->AdvancedSearch->SearchValue = @$filter["x_index_number"];
		$this->index_number->AdvancedSearch->SearchOperator = @$filter["z_index_number"];
		$this->index_number->AdvancedSearch->SearchCondition = @$filter["v_index_number"];
		$this->index_number->AdvancedSearch->SearchValue2 = @$filter["y_index_number"];
		$this->index_number->AdvancedSearch->SearchOperator2 = @$filter["w_index_number"];
		$this->index_number->AdvancedSearch->Save();

		// Field ss_course
		$this->ss_course->AdvancedSearch->SearchValue = @$filter["x_ss_course"];
		$this->ss_course->AdvancedSearch->SearchOperator = @$filter["z_ss_course"];
		$this->ss_course->AdvancedSearch->SearchCondition = @$filter["v_ss_course"];
		$this->ss_course->AdvancedSearch->SearchValue2 = @$filter["y_ss_course"];
		$this->ss_course->AdvancedSearch->SearchOperator2 = @$filter["w_ss_course"];
		$this->ss_course->AdvancedSearch->Save();

		// Field aggregate
		$this->aggregate->AdvancedSearch->SearchValue = @$filter["x_aggregate"];
		$this->aggregate->AdvancedSearch->SearchOperator = @$filter["z_aggregate"];
		$this->aggregate->AdvancedSearch->SearchCondition = @$filter["v_aggregate"];
		$this->aggregate->AdvancedSearch->SearchValue2 = @$filter["y_aggregate"];
		$this->aggregate->AdvancedSearch->SearchOperator2 = @$filter["w_aggregate"];
		$this->aggregate->AdvancedSearch->Save();

		// Field certificate
		$this->certificate->AdvancedSearch->SearchValue = @$filter["x_certificate"];
		$this->certificate->AdvancedSearch->SearchOperator = @$filter["z_certificate"];
		$this->certificate->AdvancedSearch->SearchCondition = @$filter["v_certificate"];
		$this->certificate->AdvancedSearch->SearchValue2 = @$filter["y_certificate"];
		$this->certificate->AdvancedSearch->SearchOperator2 = @$filter["w_certificate"];
		$this->certificate->AdvancedSearch->Save();

		// Field upload_certificate
		$this->upload_certificate->AdvancedSearch->SearchValue = @$filter["x_upload_certificate"];
		$this->upload_certificate->AdvancedSearch->SearchOperator = @$filter["z_upload_certificate"];
		$this->upload_certificate->AdvancedSearch->SearchCondition = @$filter["v_upload_certificate"];
		$this->upload_certificate->AdvancedSearch->SearchValue2 = @$filter["y_upload_certificate"];
		$this->upload_certificate->AdvancedSearch->SearchOperator2 = @$filter["w_upload_certificate"];
		$this->upload_certificate->AdvancedSearch->Save();

		// Field application_status
		$this->application_status->AdvancedSearch->SearchValue = @$filter["x_application_status"];
		$this->application_status->AdvancedSearch->SearchOperator = @$filter["z_application_status"];
		$this->application_status->AdvancedSearch->SearchCondition = @$filter["v_application_status"];
		$this->application_status->AdvancedSearch->SearchValue2 = @$filter["y_application_status"];
		$this->application_status->AdvancedSearch->SearchOperator2 = @$filter["w_application_status"];
		$this->application_status->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->program_choice, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->full_name, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->secondary_School, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->graduation_year, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->index_number, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->ss_course, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->aggregate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->certificate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->upload_certificate, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->application_status, $arKeywords, $type);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSQL(&$Where, &$Fld, $arKeywords, $type) {
		global $EW_BASIC_SEARCH_IGNORE_PATTERN;
		$sDefCond = ($type == "OR") ? "OR" : "AND";
		$arSQL = array(); // Array for SQL parts
		$arCond = array(); // Array for search conditions
		$cnt = count($arKeywords);
		$j = 0; // Number of SQL parts
		for ($i = 0; $i < $cnt; $i++) {
			$Keyword = $arKeywords[$i];
			$Keyword = trim($Keyword);
			if ($EW_BASIC_SEARCH_IGNORE_PATTERN <> "") {
				$Keyword = preg_replace($EW_BASIC_SEARCH_IGNORE_PATTERN, "\\", $Keyword);
				$ar = explode("\\", $Keyword);
			} else {
				$ar = array($Keyword);
			}
			foreach ($ar as $Keyword) {
				if ($Keyword <> "") {
					$sWrk = "";
					if ($Keyword == "OR" && $type == "") {
						if ($j > 0)
							$arCond[$j-1] = "OR";
					} elseif ($Keyword == EW_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NULL";
					} elseif ($Keyword == EW_NOT_NULL_VALUE) {
						$sWrk = $Fld->FldExpression . " IS NOT NULL";
					} elseif ($Fld->FldIsVirtual) {
						$sWrk = $Fld->FldVirtualExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					} elseif ($Fld->FldDataType != EW_DATATYPE_NUMBER || is_numeric($Keyword)) {
						$sWrk = $Fld->FldBasicSearchExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING, $this->DBID), $this->DBID);
					}
					if ($sWrk <> "") {
						$arSQL[$j] = $sWrk;
						$arCond[$j] = $sDefCond;
						$j += 1;
					}
				}
			}
		}
		$cnt = count($arSQL);
		$bQuoted = FALSE;
		$sSql = "";
		if ($cnt > 0) {
			for ($i = 0; $i < $cnt-1; $i++) {
				if ($arCond[$i] == "OR") {
					if (!$bQuoted) $sSql .= "(";
					$bQuoted = TRUE;
				}
				$sSql .= $arSQL[$i];
				if ($bQuoted && $arCond[$i] <> "OR") {
					$sSql .= ")";
					$bQuoted = FALSE;
				}
				$sSql .= " " . $arCond[$i] . " ";
			}
			$sSql .= $arSQL[$cnt-1];
			if ($bQuoted)
				$sSql .= ")";
		}
		if ($sSql <> "") {
			if ($Where <> "") $Where .= " OR ";
			$Where .= "(" . $sSql . ")";
		}
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere($Default = FALSE) {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = ($Default) ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
		$sSearchType = ($Default) ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

		// Get search SQL
		if ($sSearchKeyword <> "") {
			$ar = $this->BasicSearch->KeywordList($Default);

			// Search keyword in any fields
			if (($sSearchType == "OR" || $sSearchType == "AND") && $this->BasicSearch->BasicSearchAnyFields) {
				foreach ($ar as $sKeyword) {
					if ($sKeyword <> "") {
						if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
						$sSearchStr .= "(" . $this->BasicSearchSQL(array($sKeyword), $sSearchType) . ")";
					}
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($ar, $sSearchType);
			}
			if (!$Default && in_array($this->Command, array("", "reset", "resetall"))) $this->Command = "search";
		}
		if (!$Default && $this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {
		$this->RestoreSearch = TRUE;

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetupSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = @$_GET["order"];
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->program_choice); // program_choice
			$this->UpdateSort($this->full_name); // full name
			$this->UpdateSort($this->secondary_School); // secondary_School
			$this->UpdateSort($this->graduation_year); // graduation_year
			$this->UpdateSort($this->index_number); // index_number
			$this->UpdateSort($this->ss_course); // ss_course
			$this->UpdateSort($this->aggregate); // aggregate
			$this->UpdateSort($this->certificate); // certificate
			$this->UpdateSort($this->upload_certificate); // upload_certificate
			$this->UpdateSort($this->application_status); // application_status
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->getSqlOrderBy() <> "") {
				$sOrderBy = $this->getSqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// - cmd=reset (Reset search parameters)
	// - cmd=resetall (Reset search and master/detail parameters)
	// - cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->program_choice->setSort("");
				$this->full_name->setSort("");
				$this->secondary_School->setSort("");
				$this->graduation_year->setSort("");
				$this->index_number->setSort("");
				$this->ss_course->setSort("");
				$this->aggregate->setSort("");
				$this->certificate->setSort("");
				$this->upload_certificate->setSort("");
				$this->application_status->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Add group option item
		$item = &$this->ListOptions->Add($this->ListOptions->GroupOptionName);
		$item->Body = "";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// List actions
		$item = &$this->ListOptions->Add("listactions");
		$item->CssClass = "text-nowrap";
		$item->OnLeft = FALSE;
		$item->Visible = FALSE;
		$item->ShowInButtonGroup = FALSE;
		$item->ShowInDropDown = FALSE;

		// "checkbox"
		$item = &$this->ListOptions->Add("checkbox");
		$item->Visible = FALSE;
		$item->OnLeft = FALSE;
		$item->Header = "<input type=\"checkbox\" name=\"key\" id=\"key\" onclick=\"ew_SelectAllKey(this);\">";
		$item->ShowInDropDown = FALSE;
		$item->ShowInButtonGroup = FALSE;

		// Drop down button for ListOptions
		$this->ListOptions->UseImageAndText = TRUE;
		$this->ListOptions->UseDropDownButton = FALSE;
		$this->ListOptions->DropDownButtonPhrase = $Language->Phrase("ButtonListOptions");
		$this->ListOptions->UseButtonGroup = FALSE;
		if ($this->ListOptions->UseButtonGroup && ew_IsMobile())
			$this->ListOptions->UseDropDownButton = TRUE;
		$this->ListOptions->ButtonClass = "btn-sm"; // Class for button group

		// Call ListOptions_Load event
		$this->ListOptions_Load();
		$this->SetupListOptionsExt();
		$item = &$this->ListOptions->GetItem($this->ListOptions->GroupOptionName);
		$item->Visible = $this->ListOptions->GroupOptionVisible();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Call ListOptions_Rendering event
		$this->ListOptions_Rendering();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		$viewcaption = ew_HtmlTitle($Language->Phrase("ViewLink"));
		if ($Security->CanView()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink ewDelete\"" . "" . " title=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("DeleteLink")) . "\" href=\"" . ew_HtmlEncode($this->DeleteUrl) . "\">" . $Language->Phrase("DeleteLink") . "</a>";
		else
			$oListOpt->Body = "";

		// Set up list action buttons
		$oListOpt = &$this->ListOptions->GetItem("listactions");
		if ($oListOpt && $this->Export == "" && $this->CurrentAction == "") {
			$body = "";
			$links = array();
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_SINGLE && $listaction->Allow) {
					$action = $listaction->Action;
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode(str_replace(" ewIcon", "", $listaction->Icon)) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\"></span> " : "";
					$links[] = "<li><a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . $listaction->Caption . "</a></li>";
					if (count($links) == 1) // Single button
						$body = "<a class=\"ewAction ewListAction\" data-action=\"" . ew_HtmlEncode($action) . "\" title=\"" . ew_HtmlTitle($caption) . "\" data-caption=\"" . ew_HtmlTitle($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({key:" . $this->KeyToJson() . "}," . $listaction->ToJson(TRUE) . "));return false;\">" . $Language->Phrase("ListActionButton") . "</a>";
				}
			}
			if (count($links) > 1) { // More than one buttons, use dropdown
				$body = "<button class=\"dropdown-toggle btn btn-default btn-sm ewActions\" title=\"" . ew_HtmlTitle($Language->Phrase("ListActionButton")) . "\" data-toggle=\"dropdown\">" . $Language->Phrase("ListActionButton") . "<b class=\"caret\"></b></button>";
				$content = "";
				foreach ($links as $link)
					$content .= "<li>" . $link . "</li>";
				$body .= "<ul class=\"dropdown-menu" . ($oListOpt->OnLeft ? "" : " dropdown-menu-right") . "\">". $content . "</ul>";
				$body = "<div class=\"btn-group\">" . $body . "</div>";
			}
			if (count($links) > 0) {
				$oListOpt->Body = $body;
				$oListOpt->Visible = TRUE;
			}
		}

		// "checkbox"
		$oListOpt = &$this->ListOptions->Items["checkbox"];
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->index_number->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	// Set up other options
	function SetupOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
		$option = $options["addedit"];

		// Add
		$item = &$option->Add("add");
		$addcaption = ew_HtmlTitle($Language->Phrase("AddLink"));
		$item->Body = "<a class=\"ewAddEdit ewAdd\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . ew_HtmlEncode($this->AddUrl) . "\">" . $Language->Phrase("AddLink") . "</a>";
		$item->Visible = ($this->AddUrl <> "" && $Security->CanAdd());
		$option = $options["action"];

		// Set up options default
		foreach ($options as &$option) {
			$option->UseImageAndText = TRUE;
			$option->UseDropDownButton = FALSE;
			$option->UseButtonGroup = TRUE;
			$option->ButtonClass = "btn-sm"; // Class for button group
			$item = &$option->Add($option->GroupOptionName);
			$item->Body = "";
			$item->Visible = FALSE;
		}
		$options["addedit"]->DropDownButtonPhrase = $Language->Phrase("ButtonAddEdit");
		$options["detail"]->DropDownButtonPhrase = $Language->Phrase("ButtonDetails");
		$options["action"]->DropDownButtonPhrase = $Language->Phrase("ButtonActions");

		// Filter button
		$item = &$this->FilterOptions->Add("savecurrentfilter");
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fapplicationlistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fapplicationlistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
		$item->Visible = TRUE;
		$this->FilterOptions->UseDropDownButton = TRUE;
		$this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
		$this->FilterOptions->DropDownButtonPhrase = $Language->Phrase("Filters");

		// Add group option item
		$item = &$this->FilterOptions->Add($this->FilterOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;
	}

	// Render other options
	function RenderOtherOptions() {
		global $Language, $Security;
		$options = &$this->OtherOptions;
			$option = &$options["action"];

			// Set up list action buttons
			foreach ($this->ListActions->Items as $listaction) {
				if ($listaction->Select == EW_ACTION_MULTIPLE) {
					$item = &$option->Add("custom_" . $listaction->Action);
					$caption = $listaction->Caption;
					$icon = ($listaction->Icon <> "") ? "<span class=\"" . ew_HtmlEncode($listaction->Icon) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\"></span> " : $caption;
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fapplicationlist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
					$item->Visible = $listaction->Allow;
				}
			}

			// Hide grid edit and other options
			if ($this->TotalRecs <= 0) {
				$option = &$options["addedit"];
				$item = &$option->GetItem("gridedit");
				if ($item) $item->Visible = FALSE;
				$option = &$options["action"];
				$option->HideAllOptions();
			}
	}

	// Process list action
	function ProcessListAction() {
		global $Language, $Security;
		$userlist = "";
		$user = "";
		$sFilter = $this->GetKeyFilter();
		$UserAction = @$_POST["useraction"];
		if ($sFilter <> "" && $UserAction <> "") {

			// Check permission first
			$ActionCaption = $UserAction;
			if (array_key_exists($UserAction, $this->ListActions->Items)) {
				$ActionCaption = $this->ListActions->Items[$UserAction]->Caption;
				if (!$this->ListActions->Items[$UserAction]->Allow) {
					$errmsg = str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionNotAllowed"));
					if (@$_POST["ajax"] == $UserAction) // Ajax
						echo "<p class=\"text-danger\">" . $errmsg . "</p>";
					else
						$this->setFailureMessage($errmsg);
					return FALSE;
				}
			}
			$this->CurrentFilter = $sFilter;
			$sSql = $this->SQL();
			$conn = &$this->Connection();
			$conn->raiseErrorFn = $GLOBALS["EW_ERROR_FN"];
			$rs = $conn->Execute($sSql);
			$conn->raiseErrorFn = '';
			$this->CurrentAction = $UserAction;

			// Call row action event
			if ($rs && !$rs->EOF) {
				$conn->BeginTrans();
				$this->SelectedCount = $rs->RecordCount();
				$this->SelectedIndex = 0;
				while (!$rs->EOF) {
					$this->SelectedIndex++;
					$row = $rs->fields;
					$Processed = $this->Row_CustomAction($UserAction, $row);
					if (!$Processed) break;
					$rs->MoveNext();
				}
				if ($Processed) {
					$conn->CommitTrans(); // Commit the changes
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionCompleted"))); // Set up success message
				} else {
					$conn->RollbackTrans(); // Rollback changes

					// Set up error message
					if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

						// Use the message, do nothing
					} elseif ($this->CancelMessage <> "") {
						$this->setFailureMessage($this->CancelMessage);
						$this->CancelMessage = "";
					} else {
						$this->setFailureMessage(str_replace('%s', $ActionCaption, $Language->Phrase("CustomActionFailed")));
					}
				}
			}
			if ($rs)
				$rs->Close();
			$this->CurrentAction = ""; // Clear action
			if (@$_POST["ajax"] == $UserAction) { // Ajax
				if ($this->getSuccessMessage() <> "") {
					echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
					$this->ClearSuccessMessage(); // Clear message
				}
				if ($this->getFailureMessage() <> "") {
					echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
					$this->ClearFailureMessage(); // Clear message
				}
				return TRUE;
			}
		}
		return FALSE; // Not ajax request
	}

	// Set up search options
	function SetupSearchOptions() {
		global $Language;
		$this->SearchOptions = new cListOptions();
		$this->SearchOptions->Tag = "div";
		$this->SearchOptions->TagClassName = "ewSearchOption";

		// Search button
		$item = &$this->SearchOptions->Add("searchtoggle");
		$SearchToggleClass = ($this->SearchWhere <> "") ? " active" : " active";
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fapplicationlistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
		$item->Visible = TRUE;

		// Show all button
		$item = &$this->SearchOptions->Add("showall");
		$item->Body = "<a class=\"btn btn-default ewShowAll\" title=\"" . $Language->Phrase("ShowAll") . "\" data-caption=\"" . $Language->Phrase("ShowAll") . "\" href=\"" . $this->PageUrl() . "cmd=reset\">" . $Language->Phrase("ShowAllBtn") . "</a>";
		$item->Visible = ($this->SearchWhere <> $this->DefaultSearchWhere && $this->SearchWhere <> "0=101");

		// Button group for search
		$this->SearchOptions->UseDropDownButton = FALSE;
		$this->SearchOptions->UseImageAndText = TRUE;
		$this->SearchOptions->UseButtonGroup = TRUE;
		$this->SearchOptions->DropDownButtonPhrase = $Language->Phrase("ButtonSearch");

		// Add group option item
		$item = &$this->SearchOptions->Add($this->SearchOptions->GroupOptionName);
		$item->Body = "";
		$item->Visible = FALSE;

		// Hide search options
		if ($this->Export <> "" || $this->CurrentAction <> "")
			$this->SearchOptions->HideAllOptions();
		global $Security;
		if (!$Security->CanSearch()) {
			$this->SearchOptions->HideAllOptions();
			$this->FilterOptions->HideAllOptions();
		}
	}

	function SetupListOptionsExt() {
		global $Security, $Language;
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
	}

	// Set up starting record parameters
	function SetupStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "" && $this->Command == "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
				$this->upload_certificate->LinkAttrs["data-rel"] = "application_x" . $this->RowCnt . "_upload_certificate";
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

	// Set up Breadcrumb
	function SetupBreadcrumb() {
		global $Breadcrumb, $Language;
		$Breadcrumb = new cBreadcrumb();
		$url = substr(ew_CurrentUrl(), strrpos(ew_CurrentUrl(), "/")+1);
		$url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
		$Breadcrumb->Add("list", $this->TableVar, $url, "", $this->TableVar, TRUE);
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendering event
	function ListOptions_Rendering() {

		//$GLOBALS["xxx_grid"]->DetailAdd = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailEdit = (...condition...); // Set to TRUE or FALSE conditionally
		//$GLOBALS["xxx_grid"]->DetailView = (...condition...); // Set to TRUE or FALSE conditionally

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example:
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}

	// Row Custom Action event
	function Row_CustomAction($action, $row) {

		// Return FALSE to abort
		return TRUE;
	}

	// Page Exporting event
	// $this->ExportDoc = export document object
	function Page_Exporting() {

		//$this->ExportDoc->Text = "my header"; // Export header
		//return FALSE; // Return FALSE to skip default export and use Row_Export event

		return TRUE; // Return TRUE to use default export and skip Row_Export event
	}

	// Row Export event
	// $this->ExportDoc = export document object
	function Row_Export($rs) {

		//$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
	}

	// Page Exported event
	// $this->ExportDoc = export document object
	function Page_Exported() {

		//$this->ExportDoc->Text .= "my footer"; // Export footer
		//echo $this->ExportDoc->Text;

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($application_list)) $application_list = new capplication_list();

// Page init
$application_list->Page_Init();

// Page main
$application_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$application_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fapplicationlist = new ew_Form("fapplicationlist", "list");
fapplicationlist.FormKeyCountName = '<?php echo $application_list->FormKeyCountName ?>';

// Form_CustomValidate event
fapplicationlist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fapplicationlist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fapplicationlist.Lists["x_program_choice"] = {"LinkField":"x_program","Ajax":true,"AutoFill":false,"DisplayFields":["x_program","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"programs"};
fapplicationlist.Lists["x_program_choice"].Data = "<?php echo $application_list->program_choice->LookupFilterQuery(FALSE, "list") ?>";
fapplicationlist.Lists["x_application_status"] = {"LinkField":"x_admmision_status","Ajax":true,"AutoFill":false,"DisplayFields":["x_admmision_status","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"status"};
fapplicationlist.Lists["x_application_status"].Data = "<?php echo $application_list->application_status->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fapplicationlistsrch = new ew_Form("fapplicationlistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($application_list->TotalRecs > 0 && $application_list->ExportOptions->Visible()) { ?>
<?php $application_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($application_list->SearchOptions->Visible()) { ?>
<?php $application_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($application_list->FilterOptions->Visible()) { ?>
<?php $application_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $application_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($application_list->TotalRecs <= 0)
			$application_list->TotalRecs = $application->ListRecordCount();
	} else {
		if (!$application_list->Recordset && ($application_list->Recordset = $application_list->LoadRecordset()))
			$application_list->TotalRecs = $application_list->Recordset->RecordCount();
	}
	$application_list->StartRec = 1;
	if ($application_list->DisplayRecs <= 0 || ($application->Export <> "" && $application->ExportAll)) // Display all records
		$application_list->DisplayRecs = $application_list->TotalRecs;
	if (!($application->Export <> "" && $application->ExportAll))
		$application_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$application_list->Recordset = $application_list->LoadRecordset($application_list->StartRec-1, $application_list->DisplayRecs);

	// Set no record found message
	if ($application->CurrentAction == "" && $application_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$application_list->setWarningMessage(ew_DeniedMsg());
		if ($application_list->SearchWhere == "0=101")
			$application_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$application_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$application_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($application->Export == "" && $application->CurrentAction == "") { ?>
<form name="fapplicationlistsrch" id="fapplicationlistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($application_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fapplicationlistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="application">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($application_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($application_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $application_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($application_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($application_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($application_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($application_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
		</ul>
	<button class="btn btn-primary ewButton" name="btnsubmit" id="btnsubmit" type="submit"><?php echo $Language->Phrase("SearchBtn") ?></button>
	</div>
	</div>
</div>
	</div>
</div>
</form>
<?php } ?>
<?php } ?>
<?php $application_list->ShowPageHeader(); ?>
<?php
$application_list->ShowMessage();
?>
<?php if ($application_list->TotalRecs > 0 || $application->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($application_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> application">
<form name="fapplicationlist" id="fapplicationlist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($application_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $application_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="application">
<div id="gmp_application" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($application_list->TotalRecs > 0 || $application->CurrentAction == "gridedit") { ?>
<table id="tbl_applicationlist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$application_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$application_list->RenderListOptions();

// Render list options (header, left)
$application_list->ListOptions->Render("header", "left");
?>
<?php if ($application->program_choice->Visible) { // program_choice ?>
	<?php if ($application->SortUrl($application->program_choice) == "") { ?>
		<th data-name="program_choice" class="<?php echo $application->program_choice->HeaderCellClass() ?>"><div id="elh_application_program_choice" class="application_program_choice"><div class="ewTableHeaderCaption"><?php echo $application->program_choice->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="program_choice" class="<?php echo $application->program_choice->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->program_choice) ?>',1);"><div id="elh_application_program_choice" class="application_program_choice">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->program_choice->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($application->program_choice->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->program_choice->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($application->full_name->Visible) { // full name ?>
	<?php if ($application->SortUrl($application->full_name) == "") { ?>
		<th data-name="full_name" class="<?php echo $application->full_name->HeaderCellClass() ?>"><div id="elh_application_full_name" class="application_full_name"><div class="ewTableHeaderCaption"><?php echo $application->full_name->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="full_name" class="<?php echo $application->full_name->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->full_name) ?>',1);"><div id="elh_application_full_name" class="application_full_name">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->full_name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($application->full_name->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->full_name->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($application->secondary_School->Visible) { // secondary_School ?>
	<?php if ($application->SortUrl($application->secondary_School) == "") { ?>
		<th data-name="secondary_School" class="<?php echo $application->secondary_School->HeaderCellClass() ?>"><div id="elh_application_secondary_School" class="application_secondary_School"><div class="ewTableHeaderCaption"><?php echo $application->secondary_School->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="secondary_School" class="<?php echo $application->secondary_School->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->secondary_School) ?>',1);"><div id="elh_application_secondary_School" class="application_secondary_School">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->secondary_School->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($application->secondary_School->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->secondary_School->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($application->graduation_year->Visible) { // graduation_year ?>
	<?php if ($application->SortUrl($application->graduation_year) == "") { ?>
		<th data-name="graduation_year" class="<?php echo $application->graduation_year->HeaderCellClass() ?>"><div id="elh_application_graduation_year" class="application_graduation_year"><div class="ewTableHeaderCaption"><?php echo $application->graduation_year->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="graduation_year" class="<?php echo $application->graduation_year->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->graduation_year) ?>',1);"><div id="elh_application_graduation_year" class="application_graduation_year">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->graduation_year->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($application->graduation_year->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->graduation_year->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($application->index_number->Visible) { // index_number ?>
	<?php if ($application->SortUrl($application->index_number) == "") { ?>
		<th data-name="index_number" class="<?php echo $application->index_number->HeaderCellClass() ?>"><div id="elh_application_index_number" class="application_index_number"><div class="ewTableHeaderCaption"><?php echo $application->index_number->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="index_number" class="<?php echo $application->index_number->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->index_number) ?>',1);"><div id="elh_application_index_number" class="application_index_number">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->index_number->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($application->index_number->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->index_number->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($application->ss_course->Visible) { // ss_course ?>
	<?php if ($application->SortUrl($application->ss_course) == "") { ?>
		<th data-name="ss_course" class="<?php echo $application->ss_course->HeaderCellClass() ?>"><div id="elh_application_ss_course" class="application_ss_course"><div class="ewTableHeaderCaption"><?php echo $application->ss_course->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="ss_course" class="<?php echo $application->ss_course->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->ss_course) ?>',1);"><div id="elh_application_ss_course" class="application_ss_course">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->ss_course->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($application->ss_course->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->ss_course->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($application->aggregate->Visible) { // aggregate ?>
	<?php if ($application->SortUrl($application->aggregate) == "") { ?>
		<th data-name="aggregate" class="<?php echo $application->aggregate->HeaderCellClass() ?>"><div id="elh_application_aggregate" class="application_aggregate"><div class="ewTableHeaderCaption"><?php echo $application->aggregate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="aggregate" class="<?php echo $application->aggregate->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->aggregate) ?>',1);"><div id="elh_application_aggregate" class="application_aggregate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->aggregate->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($application->aggregate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->aggregate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($application->certificate->Visible) { // certificate ?>
	<?php if ($application->SortUrl($application->certificate) == "") { ?>
		<th data-name="certificate" class="<?php echo $application->certificate->HeaderCellClass() ?>"><div id="elh_application_certificate" class="application_certificate"><div class="ewTableHeaderCaption"><?php echo $application->certificate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="certificate" class="<?php echo $application->certificate->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->certificate) ?>',1);"><div id="elh_application_certificate" class="application_certificate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->certificate->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($application->certificate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->certificate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($application->upload_certificate->Visible) { // upload_certificate ?>
	<?php if ($application->SortUrl($application->upload_certificate) == "") { ?>
		<th data-name="upload_certificate" class="<?php echo $application->upload_certificate->HeaderCellClass() ?>"><div id="elh_application_upload_certificate" class="application_upload_certificate"><div class="ewTableHeaderCaption"><?php echo $application->upload_certificate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="upload_certificate" class="<?php echo $application->upload_certificate->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->upload_certificate) ?>',1);"><div id="elh_application_upload_certificate" class="application_upload_certificate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->upload_certificate->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($application->upload_certificate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->upload_certificate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($application->application_status->Visible) { // application_status ?>
	<?php if ($application->SortUrl($application->application_status) == "") { ?>
		<th data-name="application_status" class="<?php echo $application->application_status->HeaderCellClass() ?>"><div id="elh_application_application_status" class="application_application_status"><div class="ewTableHeaderCaption"><?php echo $application->application_status->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="application_status" class="<?php echo $application->application_status->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $application->SortUrl($application->application_status) ?>',1);"><div id="elh_application_application_status" class="application_application_status">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $application->application_status->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($application->application_status->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($application->application_status->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$application_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($application->ExportAll && $application->Export <> "") {
	$application_list->StopRec = $application_list->TotalRecs;
} else {

	// Set the last record to display
	if ($application_list->TotalRecs > $application_list->StartRec + $application_list->DisplayRecs - 1)
		$application_list->StopRec = $application_list->StartRec + $application_list->DisplayRecs - 1;
	else
		$application_list->StopRec = $application_list->TotalRecs;
}
$application_list->RecCnt = $application_list->StartRec - 1;
if ($application_list->Recordset && !$application_list->Recordset->EOF) {
	$application_list->Recordset->MoveFirst();
	$bSelectLimit = $application_list->UseSelectLimit;
	if (!$bSelectLimit && $application_list->StartRec > 1)
		$application_list->Recordset->Move($application_list->StartRec - 1);
} elseif (!$application->AllowAddDeleteRow && $application_list->StopRec == 0) {
	$application_list->StopRec = $application->GridAddRowCount;
}

// Initialize aggregate
$application->RowType = EW_ROWTYPE_AGGREGATEINIT;
$application->ResetAttrs();
$application_list->RenderRow();
while ($application_list->RecCnt < $application_list->StopRec) {
	$application_list->RecCnt++;
	if (intval($application_list->RecCnt) >= intval($application_list->StartRec)) {
		$application_list->RowCnt++;

		// Set up key count
		$application_list->KeyCount = $application_list->RowIndex;

		// Init row class and style
		$application->ResetAttrs();
		$application->CssClass = "";
		if ($application->CurrentAction == "gridadd") {
		} else {
			$application_list->LoadRowValues($application_list->Recordset); // Load row values
		}
		$application->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$application->RowAttrs = array_merge($application->RowAttrs, array('data-rowindex'=>$application_list->RowCnt, 'id'=>'r' . $application_list->RowCnt . '_application', 'data-rowtype'=>$application->RowType));

		// Render row
		$application_list->RenderRow();

		// Render list options
		$application_list->RenderListOptions();
?>
	<tr<?php echo $application->RowAttributes() ?>>
<?php

// Render list options (body, left)
$application_list->ListOptions->Render("body", "left", $application_list->RowCnt);
?>
	<?php if ($application->program_choice->Visible) { // program_choice ?>
		<td data-name="program_choice"<?php echo $application->program_choice->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_program_choice" class="application_program_choice">
<span<?php echo $application->program_choice->ViewAttributes() ?>>
<?php echo $application->program_choice->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($application->full_name->Visible) { // full name ?>
		<td data-name="full_name"<?php echo $application->full_name->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_full_name" class="application_full_name">
<span<?php echo $application->full_name->ViewAttributes() ?>>
<?php echo $application->full_name->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($application->secondary_School->Visible) { // secondary_School ?>
		<td data-name="secondary_School"<?php echo $application->secondary_School->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_secondary_School" class="application_secondary_School">
<span<?php echo $application->secondary_School->ViewAttributes() ?>>
<?php echo $application->secondary_School->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($application->graduation_year->Visible) { // graduation_year ?>
		<td data-name="graduation_year"<?php echo $application->graduation_year->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_graduation_year" class="application_graduation_year">
<span<?php echo $application->graduation_year->ViewAttributes() ?>>
<?php echo $application->graduation_year->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($application->index_number->Visible) { // index_number ?>
		<td data-name="index_number"<?php echo $application->index_number->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_index_number" class="application_index_number">
<span<?php echo $application->index_number->ViewAttributes() ?>>
<?php echo $application->index_number->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($application->ss_course->Visible) { // ss_course ?>
		<td data-name="ss_course"<?php echo $application->ss_course->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_ss_course" class="application_ss_course">
<span<?php echo $application->ss_course->ViewAttributes() ?>>
<?php echo $application->ss_course->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($application->aggregate->Visible) { // aggregate ?>
		<td data-name="aggregate"<?php echo $application->aggregate->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_aggregate" class="application_aggregate">
<span<?php echo $application->aggregate->ViewAttributes() ?>>
<?php echo $application->aggregate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($application->certificate->Visible) { // certificate ?>
		<td data-name="certificate"<?php echo $application->certificate->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_certificate" class="application_certificate">
<span<?php echo $application->certificate->ViewAttributes() ?>>
<?php echo $application->certificate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($application->upload_certificate->Visible) { // upload_certificate ?>
		<td data-name="upload_certificate"<?php echo $application->upload_certificate->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_upload_certificate" class="application_upload_certificate">
<span>
<?php echo ew_GetFileViewTag($application->upload_certificate, $application->upload_certificate->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($application->application_status->Visible) { // application_status ?>
		<td data-name="application_status"<?php echo $application->application_status->CellAttributes() ?>>
<span id="el<?php echo $application_list->RowCnt ?>_application_application_status" class="application_application_status">
<span<?php echo $application->application_status->ViewAttributes() ?>>
<?php echo $application->application_status->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$application_list->ListOptions->Render("body", "right", $application_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($application->CurrentAction <> "gridadd")
		$application_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($application->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($application_list->Recordset)
	$application_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($application->CurrentAction <> "gridadd" && $application->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($application_list->Pager)) $application_list->Pager = new cPrevNextPager($application_list->StartRec, $application_list->DisplayRecs, $application_list->TotalRecs, $application_list->AutoHidePager) ?>
<?php if ($application_list->Pager->RecordCount > 0 && $application_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($application_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $application_list->PageUrl() ?>start=<?php echo $application_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($application_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $application_list->PageUrl() ?>start=<?php echo $application_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $application_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($application_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $application_list->PageUrl() ?>start=<?php echo $application_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($application_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $application_list->PageUrl() ?>start=<?php echo $application_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $application_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($application_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $application_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $application_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $application_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($application_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($application_list->TotalRecs == 0 && $application->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($application_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fapplicationlistsrch.FilterList = <?php echo $application_list->GetFilterList() ?>;
fapplicationlistsrch.Init();
fapplicationlist.Init();
</script>
<?php
$application_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$application_list->Page_Terminate();
?>
