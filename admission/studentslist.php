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

$students_list = NULL; // Initialize page object first

class cstudents_list extends cstudents {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = '{F31DB578-461D-4551-B52B-112914F68329}';

	// Table name
	var $TableName = 'students';

	// Page object name
	var $PageObjName = 'students_list';

	// Grid form hidden field names
	var $FormName = 'fstudentslist';
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

		// Table object (students)
		if (!isset($GLOBALS["students"]) || get_class($GLOBALS["students"]) == "cstudents") {
			$GLOBALS["students"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["students"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "studentsadd.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "studentsdelete.php";
		$this->MultiUpdateUrl = "studentsupdate.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

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
		$this->FilterOptions->TagClassName = "ewFilterOption fstudentslistsrch";

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
			if (strval($Security->CurrentUserID()) == "") {
				$this->setFailureMessage(ew_DeniedMsg()); // Set no permission
				$this->Page_Terminate();
			}
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
		$this->StudentID->SetVisibility();
		if ($this->IsAdd() || $this->IsCopy() || $this->IsGridAdd())
			$this->StudentID->Visible = FALSE;
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
			$this->StudentID->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->StudentID->FormValue))
				return FALSE;
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
			$sSavedFilterList = $UserProfile->GetSearchFilters(CurrentUserName(), "fstudentslistsrch");
		$sFilterList = ew_Concat($sFilterList, $this->StudentID->AdvancedSearch->ToJson(), ","); // Field StudentID
		$sFilterList = ew_Concat($sFilterList, $this->FirstName->AdvancedSearch->ToJson(), ","); // Field FirstName
		$sFilterList = ew_Concat($sFilterList, $this->LastName->AdvancedSearch->ToJson(), ","); // Field LastName
		$sFilterList = ew_Concat($sFilterList, $this->BithDate->AdvancedSearch->ToJson(), ","); // Field BithDate
		$sFilterList = ew_Concat($sFilterList, $this->Address->AdvancedSearch->ToJson(), ","); // Field Address
		$sFilterList = ew_Concat($sFilterList, $this->Username->AdvancedSearch->ToJson(), ","); // Field Username
		$sFilterList = ew_Concat($sFilterList, $this->Password->AdvancedSearch->ToJson(), ","); // Field Password
		$sFilterList = ew_Concat($sFilterList, $this->Country->AdvancedSearch->ToJson(), ","); // Field Country
		$sFilterList = ew_Concat($sFilterList, $this->_Email->AdvancedSearch->ToJson(), ","); // Field Email
		$sFilterList = ew_Concat($sFilterList, $this->photo->AdvancedSearch->ToJson(), ","); // Field photo
		$sFilterList = ew_Concat($sFilterList, $this->userlevel_id->AdvancedSearch->ToJson(), ","); // Field userlevel_id
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
			$UserProfile->SetSearchFilters(CurrentUserName(), "fstudentslistsrch", $filters);

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

		// Field StudentID
		$this->StudentID->AdvancedSearch->SearchValue = @$filter["x_StudentID"];
		$this->StudentID->AdvancedSearch->SearchOperator = @$filter["z_StudentID"];
		$this->StudentID->AdvancedSearch->SearchCondition = @$filter["v_StudentID"];
		$this->StudentID->AdvancedSearch->SearchValue2 = @$filter["y_StudentID"];
		$this->StudentID->AdvancedSearch->SearchOperator2 = @$filter["w_StudentID"];
		$this->StudentID->AdvancedSearch->Save();

		// Field FirstName
		$this->FirstName->AdvancedSearch->SearchValue = @$filter["x_FirstName"];
		$this->FirstName->AdvancedSearch->SearchOperator = @$filter["z_FirstName"];
		$this->FirstName->AdvancedSearch->SearchCondition = @$filter["v_FirstName"];
		$this->FirstName->AdvancedSearch->SearchValue2 = @$filter["y_FirstName"];
		$this->FirstName->AdvancedSearch->SearchOperator2 = @$filter["w_FirstName"];
		$this->FirstName->AdvancedSearch->Save();

		// Field LastName
		$this->LastName->AdvancedSearch->SearchValue = @$filter["x_LastName"];
		$this->LastName->AdvancedSearch->SearchOperator = @$filter["z_LastName"];
		$this->LastName->AdvancedSearch->SearchCondition = @$filter["v_LastName"];
		$this->LastName->AdvancedSearch->SearchValue2 = @$filter["y_LastName"];
		$this->LastName->AdvancedSearch->SearchOperator2 = @$filter["w_LastName"];
		$this->LastName->AdvancedSearch->Save();

		// Field BithDate
		$this->BithDate->AdvancedSearch->SearchValue = @$filter["x_BithDate"];
		$this->BithDate->AdvancedSearch->SearchOperator = @$filter["z_BithDate"];
		$this->BithDate->AdvancedSearch->SearchCondition = @$filter["v_BithDate"];
		$this->BithDate->AdvancedSearch->SearchValue2 = @$filter["y_BithDate"];
		$this->BithDate->AdvancedSearch->SearchOperator2 = @$filter["w_BithDate"];
		$this->BithDate->AdvancedSearch->Save();

		// Field Address
		$this->Address->AdvancedSearch->SearchValue = @$filter["x_Address"];
		$this->Address->AdvancedSearch->SearchOperator = @$filter["z_Address"];
		$this->Address->AdvancedSearch->SearchCondition = @$filter["v_Address"];
		$this->Address->AdvancedSearch->SearchValue2 = @$filter["y_Address"];
		$this->Address->AdvancedSearch->SearchOperator2 = @$filter["w_Address"];
		$this->Address->AdvancedSearch->Save();

		// Field Username
		$this->Username->AdvancedSearch->SearchValue = @$filter["x_Username"];
		$this->Username->AdvancedSearch->SearchOperator = @$filter["z_Username"];
		$this->Username->AdvancedSearch->SearchCondition = @$filter["v_Username"];
		$this->Username->AdvancedSearch->SearchValue2 = @$filter["y_Username"];
		$this->Username->AdvancedSearch->SearchOperator2 = @$filter["w_Username"];
		$this->Username->AdvancedSearch->Save();

		// Field Password
		$this->Password->AdvancedSearch->SearchValue = @$filter["x_Password"];
		$this->Password->AdvancedSearch->SearchOperator = @$filter["z_Password"];
		$this->Password->AdvancedSearch->SearchCondition = @$filter["v_Password"];
		$this->Password->AdvancedSearch->SearchValue2 = @$filter["y_Password"];
		$this->Password->AdvancedSearch->SearchOperator2 = @$filter["w_Password"];
		$this->Password->AdvancedSearch->Save();

		// Field Country
		$this->Country->AdvancedSearch->SearchValue = @$filter["x_Country"];
		$this->Country->AdvancedSearch->SearchOperator = @$filter["z_Country"];
		$this->Country->AdvancedSearch->SearchCondition = @$filter["v_Country"];
		$this->Country->AdvancedSearch->SearchValue2 = @$filter["y_Country"];
		$this->Country->AdvancedSearch->SearchOperator2 = @$filter["w_Country"];
		$this->Country->AdvancedSearch->Save();

		// Field Email
		$this->_Email->AdvancedSearch->SearchValue = @$filter["x__Email"];
		$this->_Email->AdvancedSearch->SearchOperator = @$filter["z__Email"];
		$this->_Email->AdvancedSearch->SearchCondition = @$filter["v__Email"];
		$this->_Email->AdvancedSearch->SearchValue2 = @$filter["y__Email"];
		$this->_Email->AdvancedSearch->SearchOperator2 = @$filter["w__Email"];
		$this->_Email->AdvancedSearch->Save();

		// Field photo
		$this->photo->AdvancedSearch->SearchValue = @$filter["x_photo"];
		$this->photo->AdvancedSearch->SearchOperator = @$filter["z_photo"];
		$this->photo->AdvancedSearch->SearchCondition = @$filter["v_photo"];
		$this->photo->AdvancedSearch->SearchValue2 = @$filter["y_photo"];
		$this->photo->AdvancedSearch->SearchOperator2 = @$filter["w_photo"];
		$this->photo->AdvancedSearch->Save();

		// Field userlevel_id
		$this->userlevel_id->AdvancedSearch->SearchValue = @$filter["x_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->SearchOperator = @$filter["z_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->SearchCondition = @$filter["v_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->SearchValue2 = @$filter["y_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->SearchOperator2 = @$filter["w_userlevel_id"];
		$this->userlevel_id->AdvancedSearch->Save();
		$this->BasicSearch->setKeyword(@$filter[EW_TABLE_BASIC_SEARCH]);
		$this->BasicSearch->setType(@$filter[EW_TABLE_BASIC_SEARCH_TYPE]);
	}

	// Return basic search SQL
	function BasicSearchSQL($arKeywords, $type) {
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->FirstName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->LastName, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Address, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Username, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Password, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->Country, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->_Email, $arKeywords, $type);
		$this->BuildBasicSearchSQL($sWhere, $this->photo, $arKeywords, $type);
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
			$this->UpdateSort($this->StudentID); // StudentID
			$this->UpdateSort($this->FirstName); // FirstName
			$this->UpdateSort($this->LastName); // LastName
			$this->UpdateSort($this->BithDate); // BithDate
			$this->UpdateSort($this->Address); // Address
			$this->UpdateSort($this->Username); // Username
			$this->UpdateSort($this->Password); // Password
			$this->UpdateSort($this->Country); // Country
			$this->UpdateSort($this->_Email); // Email
			$this->UpdateSort($this->photo); // photo
			$this->UpdateSort($this->userlevel_id); // userlevel_id
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
				$this->StudentID->setSort("");
				$this->FirstName->setSort("");
				$this->LastName->setSort("");
				$this->BithDate->setSort("");
				$this->Address->setSort("");
				$this->Username->setSort("");
				$this->Password->setSort("");
				$this->Country->setSort("");
				$this->_Email->setSort("");
				$this->photo->setSort("");
				$this->userlevel_id->setSort("");
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

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssClass = "text-nowrap";
		$item->Visible = $Security->CanAdd();
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
		if ($Security->CanView() && $this->ShowOptionLink('view')) {
			$oListOpt->Body = "<a class=\"ewRowLink ewView\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . ew_HtmlEncode($this->ViewUrl) . "\">" . $Language->Phrase("ViewLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		$editcaption = ew_HtmlTitle($Language->Phrase("EditLink"));
		if ($Security->CanEdit() && $this->ShowOptionLink('edit')) {
			$oListOpt->Body = "<a class=\"ewRowLink ewEdit\" title=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" data-caption=\"" . ew_HtmlTitle($Language->Phrase("EditLink")) . "\" href=\"" . ew_HtmlEncode($this->EditUrl) . "\">" . $Language->Phrase("EditLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		$copycaption = ew_HtmlTitle($Language->Phrase("CopyLink"));
		if ($Security->CanAdd() && $this->ShowOptionLink('add')) {
			$oListOpt->Body = "<a class=\"ewRowLink ewCopy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . ew_HtmlEncode($this->CopyUrl) . "\">" . $Language->Phrase("CopyLink") . "</a>";
		} else {
			$oListOpt->Body = "";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete() && $this->ShowOptionLink('delete'))
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
		$oListOpt->Body = "<input type=\"checkbox\" name=\"key_m[]\" class=\"ewMultiSelect\" value=\"" . ew_HtmlEncode($this->StudentID->CurrentValue) . "\" onclick=\"ew_ClickMultiCheckbox(event);\">";
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
		$item->Body = "<a class=\"ewSaveFilter\" data-form=\"fstudentslistsrch\" href=\"#\">" . $Language->Phrase("SaveCurrentFilter") . "</a>";
		$item->Visible = TRUE;
		$item = &$this->FilterOptions->Add("deletefilter");
		$item->Body = "<a class=\"ewDeleteFilter\" data-form=\"fstudentslistsrch\" href=\"#\">" . $Language->Phrase("DeleteFilter") . "</a>";
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
					$item->Body = "<a class=\"ewAction ewListAction\" title=\"" . ew_HtmlEncode($caption) . "\" data-caption=\"" . ew_HtmlEncode($caption) . "\" href=\"\" onclick=\"ew_SubmitAction(event,jQuery.extend({f:document.fstudentslist}," . $listaction->ToJson(TRUE) . "));return false;\">" . $icon . "</a>";
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
					$user = $row['Username'];
					if ($userlist <> "") $userlist .= ",";
					$userlist .= $user;
					if ($UserAction == "resendregisteremail")
						$Processed = FALSE;
					elseif ($UserAction == "resetconcurrentuser")
						$Processed = FALSE;
					elseif ($UserAction == "resetloginretry")
						$Processed = FALSE;
					elseif ($UserAction == "setpasswordexpired")
						$Processed = FALSE;
					else
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
		$item->Body = "<button type=\"button\" class=\"btn btn-default ewSearchToggle" . $SearchToggleClass . "\" title=\"" . $Language->Phrase("SearchPanel") . "\" data-caption=\"" . $Language->Phrase("SearchPanel") . "\" data-toggle=\"button\" data-form=\"fstudentslistsrch\">" . $Language->Phrase("SearchLink") . "</button>";
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
		$row = array();
		$row['StudentID'] = NULL;
		$row['FirstName'] = NULL;
		$row['LastName'] = NULL;
		$row['BithDate'] = NULL;
		$row['Address'] = NULL;
		$row['Username'] = NULL;
		$row['Password'] = NULL;
		$row['Country'] = NULL;
		$row['Email'] = NULL;
		$row['photo'] = NULL;
		$row['userlevel_id'] = NULL;
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
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

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
				$this->photo->LinkAttrs["data-rel"] = "students_x" . $this->RowCnt . "_photo";
				ew_AppendClass($this->photo->LinkAttrs["class"], "ewLightbox");
			}

			// userlevel_id
			$this->userlevel_id->LinkCustomAttributes = "";
			$this->userlevel_id->HrefValue = "";
			$this->userlevel_id->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
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
if (!isset($students_list)) $students_list = new cstudents_list();

// Page init
$students_list->Page_Init();

// Page main
$students_list->Page_Main();

// Global Page Rendering event (in userfn*.php)
Page_Rendering();

// Page Rendering event
$students_list->Page_Render();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Form object
var CurrentPageID = EW_PAGE_ID = "list";
var CurrentForm = fstudentslist = new ew_Form("fstudentslist", "list");
fstudentslist.FormKeyCountName = '<?php echo $students_list->FormKeyCountName ?>';

// Form_CustomValidate event
fstudentslist.Form_CustomValidate = 
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid.
 	return true;
 }

// Use JavaScript validation or not
fstudentslist.ValidateRequired = <?php echo json_encode(EW_CLIENT_VALIDATE) ?>;

// Dynamic selection lists
fstudentslist.Lists["x_userlevel_id"] = {"LinkField":"x_userlevelid","Ajax":true,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"ChildFields":[],"FilterFields":[],"Options":[],"Template":"","LinkTable":"userlevels"};
fstudentslist.Lists["x_userlevel_id"].Data = "<?php echo $students_list->userlevel_id->LookupFilterQuery(FALSE, "list") ?>";

// Form object for search
var CurrentSearchForm = fstudentslistsrch = new ew_Form("fstudentslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<div class="ewToolbar">
<?php if ($students_list->TotalRecs > 0 && $students_list->ExportOptions->Visible()) { ?>
<?php $students_list->ExportOptions->Render("body") ?>
<?php } ?>
<?php if ($students_list->SearchOptions->Visible()) { ?>
<?php $students_list->SearchOptions->Render("body") ?>
<?php } ?>
<?php if ($students_list->FilterOptions->Visible()) { ?>
<?php $students_list->FilterOptions->Render("body") ?>
<?php } ?>
<div class="clearfix"></div>
</div>
<?php
	$bSelectLimit = $students_list->UseSelectLimit;
	if ($bSelectLimit) {
		if ($students_list->TotalRecs <= 0)
			$students_list->TotalRecs = $students->ListRecordCount();
	} else {
		if (!$students_list->Recordset && ($students_list->Recordset = $students_list->LoadRecordset()))
			$students_list->TotalRecs = $students_list->Recordset->RecordCount();
	}
	$students_list->StartRec = 1;
	if ($students_list->DisplayRecs <= 0 || ($students->Export <> "" && $students->ExportAll)) // Display all records
		$students_list->DisplayRecs = $students_list->TotalRecs;
	if (!($students->Export <> "" && $students->ExportAll))
		$students_list->SetupStartRec(); // Set up start record position
	if ($bSelectLimit)
		$students_list->Recordset = $students_list->LoadRecordset($students_list->StartRec-1, $students_list->DisplayRecs);

	// Set no record found message
	if ($students->CurrentAction == "" && $students_list->TotalRecs == 0) {
		if (!$Security->CanList())
			$students_list->setWarningMessage(ew_DeniedMsg());
		if ($students_list->SearchWhere == "0=101")
			$students_list->setWarningMessage($Language->Phrase("EnterSearchCriteria"));
		else
			$students_list->setWarningMessage($Language->Phrase("NoRecord"));
	}
$students_list->RenderOtherOptions();
?>
<?php if ($Security->CanSearch()) { ?>
<?php if ($students->Export == "" && $students->CurrentAction == "") { ?>
<form name="fstudentslistsrch" id="fstudentslistsrch" class="form-inline ewForm ewExtSearchForm" action="<?php echo ew_CurrentPage() ?>">
<?php $SearchPanelClass = ($students_list->SearchWhere <> "") ? " in" : " in"; ?>
<div id="fstudentslistsrch_SearchPanel" class="ewSearchPanel collapse<?php echo $SearchPanelClass ?>">
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="students">
	<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<div class="ewQuickSearch input-group">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>" class="form-control" value="<?php echo ew_HtmlEncode($students_list->BasicSearch->getKeyword()) ?>" placeholder="<?php echo ew_HtmlEncode($Language->Phrase("Search")) ?>">
	<input type="hidden" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" id="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="<?php echo ew_HtmlEncode($students_list->BasicSearch->getType()) ?>">
	<div class="input-group-btn">
		<button type="button" data-toggle="dropdown" class="btn btn-default"><span id="searchtype"><?php echo $students_list->BasicSearch->getTypeNameShort() ?></span><span class="caret"></span></button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li<?php if ($students_list->BasicSearch->getType() == "") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this)"><?php echo $Language->Phrase("QuickSearchAuto") ?></a></li>
			<li<?php if ($students_list->BasicSearch->getType() == "=") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'=')"><?php echo $Language->Phrase("QuickSearchExact") ?></a></li>
			<li<?php if ($students_list->BasicSearch->getType() == "AND") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'AND')"><?php echo $Language->Phrase("QuickSearchAll") ?></a></li>
			<li<?php if ($students_list->BasicSearch->getType() == "OR") echo " class=\"active\""; ?>><a href="javascript:void(0);" onclick="ew_SetSearchType(this,'OR')"><?php echo $Language->Phrase("QuickSearchAny") ?></a></li>
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
<?php $students_list->ShowPageHeader(); ?>
<?php
$students_list->ShowMessage();
?>
<?php if ($students_list->TotalRecs > 0 || $students->CurrentAction <> "") { ?>
<div class="box ewBox ewGrid<?php if ($students_list->IsAddOrEdit()) { ?> ewGridAddEdit<?php } ?> students">
<form name="fstudentslist" id="fstudentslist" class="form-inline ewForm ewListForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<?php if ($students_list->CheckToken) { ?>
<input type="hidden" name="<?php echo EW_TOKEN_NAME ?>" value="<?php echo $students_list->Token ?>">
<?php } ?>
<input type="hidden" name="t" value="students">
<div id="gmp_students" class="<?php if (ew_IsResponsiveLayout()) { ?>table-responsive <?php } ?>ewGridMiddlePanel">
<?php if ($students_list->TotalRecs > 0 || $students->CurrentAction == "gridedit") { ?>
<table id="tbl_studentslist" class="table ewTable">
<thead>
	<tr class="ewTableHeader">
<?php

// Header row
$students_list->RowType = EW_ROWTYPE_HEADER;

// Render list options
$students_list->RenderListOptions();

// Render list options (header, left)
$students_list->ListOptions->Render("header", "left");
?>
<?php if ($students->StudentID->Visible) { // StudentID ?>
	<?php if ($students->SortUrl($students->StudentID) == "") { ?>
		<th data-name="StudentID" class="<?php echo $students->StudentID->HeaderCellClass() ?>"><div id="elh_students_StudentID" class="students_StudentID"><div class="ewTableHeaderCaption"><?php echo $students->StudentID->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="StudentID" class="<?php echo $students->StudentID->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->StudentID) ?>',1);"><div id="elh_students_StudentID" class="students_StudentID">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->StudentID->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($students->StudentID->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->StudentID->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->FirstName->Visible) { // FirstName ?>
	<?php if ($students->SortUrl($students->FirstName) == "") { ?>
		<th data-name="FirstName" class="<?php echo $students->FirstName->HeaderCellClass() ?>"><div id="elh_students_FirstName" class="students_FirstName"><div class="ewTableHeaderCaption"><?php echo $students->FirstName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="FirstName" class="<?php echo $students->FirstName->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->FirstName) ?>',1);"><div id="elh_students_FirstName" class="students_FirstName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->FirstName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->FirstName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->FirstName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->LastName->Visible) { // LastName ?>
	<?php if ($students->SortUrl($students->LastName) == "") { ?>
		<th data-name="LastName" class="<?php echo $students->LastName->HeaderCellClass() ?>"><div id="elh_students_LastName" class="students_LastName"><div class="ewTableHeaderCaption"><?php echo $students->LastName->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="LastName" class="<?php echo $students->LastName->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->LastName) ?>',1);"><div id="elh_students_LastName" class="students_LastName">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->LastName->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->LastName->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->LastName->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->BithDate->Visible) { // BithDate ?>
	<?php if ($students->SortUrl($students->BithDate) == "") { ?>
		<th data-name="BithDate" class="<?php echo $students->BithDate->HeaderCellClass() ?>"><div id="elh_students_BithDate" class="students_BithDate"><div class="ewTableHeaderCaption"><?php echo $students->BithDate->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="BithDate" class="<?php echo $students->BithDate->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->BithDate) ?>',1);"><div id="elh_students_BithDate" class="students_BithDate">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->BithDate->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($students->BithDate->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->BithDate->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->Address->Visible) { // Address ?>
	<?php if ($students->SortUrl($students->Address) == "") { ?>
		<th data-name="Address" class="<?php echo $students->Address->HeaderCellClass() ?>"><div id="elh_students_Address" class="students_Address"><div class="ewTableHeaderCaption"><?php echo $students->Address->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Address" class="<?php echo $students->Address->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->Address) ?>',1);"><div id="elh_students_Address" class="students_Address">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->Address->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->Address->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->Address->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->Username->Visible) { // Username ?>
	<?php if ($students->SortUrl($students->Username) == "") { ?>
		<th data-name="Username" class="<?php echo $students->Username->HeaderCellClass() ?>"><div id="elh_students_Username" class="students_Username"><div class="ewTableHeaderCaption"><?php echo $students->Username->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Username" class="<?php echo $students->Username->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->Username) ?>',1);"><div id="elh_students_Username" class="students_Username">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->Username->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->Username->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->Username->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->Password->Visible) { // Password ?>
	<?php if ($students->SortUrl($students->Password) == "") { ?>
		<th data-name="Password" class="<?php echo $students->Password->HeaderCellClass() ?>"><div id="elh_students_Password" class="students_Password"><div class="ewTableHeaderCaption"><?php echo $students->Password->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Password" class="<?php echo $students->Password->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->Password) ?>',1);"><div id="elh_students_Password" class="students_Password">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->Password->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->Password->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->Password->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->Country->Visible) { // Country ?>
	<?php if ($students->SortUrl($students->Country) == "") { ?>
		<th data-name="Country" class="<?php echo $students->Country->HeaderCellClass() ?>"><div id="elh_students_Country" class="students_Country"><div class="ewTableHeaderCaption"><?php echo $students->Country->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="Country" class="<?php echo $students->Country->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->Country) ?>',1);"><div id="elh_students_Country" class="students_Country">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->Country->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->Country->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->Country->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->_Email->Visible) { // Email ?>
	<?php if ($students->SortUrl($students->_Email) == "") { ?>
		<th data-name="_Email" class="<?php echo $students->_Email->HeaderCellClass() ?>"><div id="elh_students__Email" class="students__Email"><div class="ewTableHeaderCaption"><?php echo $students->_Email->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="_Email" class="<?php echo $students->_Email->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->_Email) ?>',1);"><div id="elh_students__Email" class="students__Email">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->_Email->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->_Email->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->_Email->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->photo->Visible) { // photo ?>
	<?php if ($students->SortUrl($students->photo) == "") { ?>
		<th data-name="photo" class="<?php echo $students->photo->HeaderCellClass() ?>"><div id="elh_students_photo" class="students_photo"><div class="ewTableHeaderCaption"><?php echo $students->photo->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="photo" class="<?php echo $students->photo->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->photo) ?>',1);"><div id="elh_students_photo" class="students_photo">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->photo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?></span><span class="ewTableHeaderSort"><?php if ($students->photo->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->photo->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php if ($students->userlevel_id->Visible) { // userlevel_id ?>
	<?php if ($students->SortUrl($students->userlevel_id) == "") { ?>
		<th data-name="userlevel_id" class="<?php echo $students->userlevel_id->HeaderCellClass() ?>"><div id="elh_students_userlevel_id" class="students_userlevel_id"><div class="ewTableHeaderCaption"><?php echo $students->userlevel_id->FldCaption() ?></div></div></th>
	<?php } else { ?>
		<th data-name="userlevel_id" class="<?php echo $students->userlevel_id->HeaderCellClass() ?>"><div class="ewPointer" onclick="ew_Sort(event,'<?php echo $students->SortUrl($students->userlevel_id) ?>',1);"><div id="elh_students_userlevel_id" class="students_userlevel_id">
			<div class="ewTableHeaderBtn"><span class="ewTableHeaderCaption"><?php echo $students->userlevel_id->FldCaption() ?></span><span class="ewTableHeaderSort"><?php if ($students->userlevel_id->getSort() == "ASC") { ?><span class="caret ewSortUp"></span><?php } elseif ($students->userlevel_id->getSort() == "DESC") { ?><span class="caret"></span><?php } ?></span></div>
		</div></div></th>
	<?php } ?>
<?php } ?>
<?php

// Render list options (header, right)
$students_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($students->ExportAll && $students->Export <> "") {
	$students_list->StopRec = $students_list->TotalRecs;
} else {

	// Set the last record to display
	if ($students_list->TotalRecs > $students_list->StartRec + $students_list->DisplayRecs - 1)
		$students_list->StopRec = $students_list->StartRec + $students_list->DisplayRecs - 1;
	else
		$students_list->StopRec = $students_list->TotalRecs;
}
$students_list->RecCnt = $students_list->StartRec - 1;
if ($students_list->Recordset && !$students_list->Recordset->EOF) {
	$students_list->Recordset->MoveFirst();
	$bSelectLimit = $students_list->UseSelectLimit;
	if (!$bSelectLimit && $students_list->StartRec > 1)
		$students_list->Recordset->Move($students_list->StartRec - 1);
} elseif (!$students->AllowAddDeleteRow && $students_list->StopRec == 0) {
	$students_list->StopRec = $students->GridAddRowCount;
}

// Initialize aggregate
$students->RowType = EW_ROWTYPE_AGGREGATEINIT;
$students->ResetAttrs();
$students_list->RenderRow();
while ($students_list->RecCnt < $students_list->StopRec) {
	$students_list->RecCnt++;
	if (intval($students_list->RecCnt) >= intval($students_list->StartRec)) {
		$students_list->RowCnt++;

		// Set up key count
		$students_list->KeyCount = $students_list->RowIndex;

		// Init row class and style
		$students->ResetAttrs();
		$students->CssClass = "";
		if ($students->CurrentAction == "gridadd") {
		} else {
			$students_list->LoadRowValues($students_list->Recordset); // Load row values
		}
		$students->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$students->RowAttrs = array_merge($students->RowAttrs, array('data-rowindex'=>$students_list->RowCnt, 'id'=>'r' . $students_list->RowCnt . '_students', 'data-rowtype'=>$students->RowType));

		// Render row
		$students_list->RenderRow();

		// Render list options
		$students_list->RenderListOptions();
?>
	<tr<?php echo $students->RowAttributes() ?>>
<?php

// Render list options (body, left)
$students_list->ListOptions->Render("body", "left", $students_list->RowCnt);
?>
	<?php if ($students->StudentID->Visible) { // StudentID ?>
		<td data-name="StudentID"<?php echo $students->StudentID->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_StudentID" class="students_StudentID">
<span<?php echo $students->StudentID->ViewAttributes() ?>>
<?php echo $students->StudentID->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($students->FirstName->Visible) { // FirstName ?>
		<td data-name="FirstName"<?php echo $students->FirstName->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_FirstName" class="students_FirstName">
<span<?php echo $students->FirstName->ViewAttributes() ?>>
<?php echo $students->FirstName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($students->LastName->Visible) { // LastName ?>
		<td data-name="LastName"<?php echo $students->LastName->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_LastName" class="students_LastName">
<span<?php echo $students->LastName->ViewAttributes() ?>>
<?php echo $students->LastName->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($students->BithDate->Visible) { // BithDate ?>
		<td data-name="BithDate"<?php echo $students->BithDate->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_BithDate" class="students_BithDate">
<span<?php echo $students->BithDate->ViewAttributes() ?>>
<?php echo $students->BithDate->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($students->Address->Visible) { // Address ?>
		<td data-name="Address"<?php echo $students->Address->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_Address" class="students_Address">
<span<?php echo $students->Address->ViewAttributes() ?>>
<?php echo $students->Address->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($students->Username->Visible) { // Username ?>
		<td data-name="Username"<?php echo $students->Username->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_Username" class="students_Username">
<span<?php echo $students->Username->ViewAttributes() ?>>
<?php echo $students->Username->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($students->Password->Visible) { // Password ?>
		<td data-name="Password"<?php echo $students->Password->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_Password" class="students_Password">
<span<?php echo $students->Password->ViewAttributes() ?>>
<?php echo $students->Password->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($students->Country->Visible) { // Country ?>
		<td data-name="Country"<?php echo $students->Country->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_Country" class="students_Country">
<span<?php echo $students->Country->ViewAttributes() ?>>
<?php echo $students->Country->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($students->_Email->Visible) { // Email ?>
		<td data-name="_Email"<?php echo $students->_Email->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students__Email" class="students__Email">
<span<?php echo $students->_Email->ViewAttributes() ?>>
<?php echo $students->_Email->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
	<?php if ($students->photo->Visible) { // photo ?>
		<td data-name="photo"<?php echo $students->photo->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_photo" class="students_photo">
<span>
<?php echo ew_GetFileViewTag($students->photo, $students->photo->ListViewValue()) ?>
</span>
</span>
</td>
	<?php } ?>
	<?php if ($students->userlevel_id->Visible) { // userlevel_id ?>
		<td data-name="userlevel_id"<?php echo $students->userlevel_id->CellAttributes() ?>>
<span id="el<?php echo $students_list->RowCnt ?>_students_userlevel_id" class="students_userlevel_id">
<span<?php echo $students->userlevel_id->ViewAttributes() ?>>
<?php echo $students->userlevel_id->ListViewValue() ?></span>
</span>
</td>
	<?php } ?>
<?php

// Render list options (body, right)
$students_list->ListOptions->Render("body", "right", $students_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($students->CurrentAction <> "gridadd")
		$students_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($students->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($students_list->Recordset)
	$students_list->Recordset->Close();
?>
<div class="box-footer ewGridLowerPanel">
<?php if ($students->CurrentAction <> "gridadd" && $students->CurrentAction <> "gridedit") { ?>
<form name="ewPagerForm" class="ewForm form-inline ewPagerForm" action="<?php echo ew_CurrentPage() ?>">
<?php if (!isset($students_list->Pager)) $students_list->Pager = new cPrevNextPager($students_list->StartRec, $students_list->DisplayRecs, $students_list->TotalRecs, $students_list->AutoHidePager) ?>
<?php if ($students_list->Pager->RecordCount > 0 && $students_list->Pager->Visible) { ?>
<div class="ewPager">
<span><?php echo $Language->Phrase("Page") ?>&nbsp;</span>
<div class="ewPrevNext"><div class="input-group">
<div class="input-group-btn">
<!--first page button-->
	<?php if ($students_list->Pager->FirstButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerFirst") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->FirstButton->Start ?>"><span class="icon-first ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerFirst") ?>"><span class="icon-first ewIcon"></span></a>
	<?php } ?>
<!--previous page button-->
	<?php if ($students_list->Pager->PrevButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerPrevious") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->PrevButton->Start ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerPrevious") ?>"><span class="icon-prev ewIcon"></span></a>
	<?php } ?>
</div>
<!--current page number-->
	<input class="form-control input-sm" type="text" name="<?php echo EW_TABLE_PAGE_NO ?>" value="<?php echo $students_list->Pager->CurrentPage ?>">
<div class="input-group-btn">
<!--next page button-->
	<?php if ($students_list->Pager->NextButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerNext") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->NextButton->Start ?>"><span class="icon-next ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerNext") ?>"><span class="icon-next ewIcon"></span></a>
	<?php } ?>
<!--last page button-->
	<?php if ($students_list->Pager->LastButton->Enabled) { ?>
	<a class="btn btn-default btn-sm" title="<?php echo $Language->Phrase("PagerLast") ?>" href="<?php echo $students_list->PageUrl() ?>start=<?php echo $students_list->Pager->LastButton->Start ?>"><span class="icon-last ewIcon"></span></a>
	<?php } else { ?>
	<a class="btn btn-default btn-sm disabled" title="<?php echo $Language->Phrase("PagerLast") ?>"><span class="icon-last ewIcon"></span></a>
	<?php } ?>
</div>
</div>
</div>
<span>&nbsp;<?php echo $Language->Phrase("of") ?>&nbsp;<?php echo $students_list->Pager->PageCount ?></span>
</div>
<?php } ?>
<?php if ($students_list->Pager->RecordCount > 0) { ?>
<div class="ewPager ewRec">
	<span><?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $students_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $students_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $students_list->Pager->RecordCount ?></span>
</div>
<?php } ?>
</form>
<?php } ?>
<div class="ewListOtherOptions">
<?php
	foreach ($students_list->OtherOptions as &$option)
		$option->Render("body", "bottom");
?>
</div>
<div class="clearfix"></div>
</div>
</div>
<?php } ?>
<?php if ($students_list->TotalRecs == 0 && $students->CurrentAction == "") { // Show other options ?>
<div class="ewListOtherOptions">
<?php
	foreach ($students_list->OtherOptions as &$option) {
		$option->ButtonClass = "";
		$option->Render("body", "");
	}
?>
</div>
<div class="clearfix"></div>
<?php } ?>
<script type="text/javascript">
fstudentslistsrch.FilterList = <?php echo $students_list->GetFilterList() ?>;
fstudentslistsrch.Init();
fstudentslist.Init();
</script>
<?php
$students_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$students_list->Page_Terminate();
?>
