<!doctype html>
<html lang="en">
<head>
    <title>Item Tracker (Stats)</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" href="images/safari-pinned-tab.svg" color="#555555">
    <meta name="apple-mobile-web-app-title" content="Item Tracker">
    <meta name="application-name" content="Item Tracker">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <!-- external javascript -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <!-- internal javascript -->
    <script src="includes/js/charts.js"></script>
    <script src="includes/js/navigation.js"></script>
    <script src="includes/js/items.js"></script>
    <script src="includes/js/main.js"></script>
    <!-- css stylesheets -->
    <link href="style/main.css" rel="stylesheet" type="text/css">
</head>
<body>
    <!-- main top nav bar -->
    <nav class="navbar bg-gradient-dark fixed-top navbar-expand-lg navbar-dark">
        <a id="link_logo" class="navbar-brand" href="index.php">
            <i class="icon-fire navbar-site-logo"></i>
        </a>
        <span id="navTextTitle">Item Tracker</span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle Navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse navbar-custom" id="navbarContent">
            <ul class="navbar-nav mr-auto">
                <li id="link_stats_li" class="nav-item active">
                    <a id="link_stats" class="nav-link" href="index.php">
                        Home <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li id="link_items_li" class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        View Items
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a id="link_notreplaced" class="dropdown-item" href="index.php?view=notreplaced">Not Replaced</a>
                        <a id="link_partial" class="dropdown-item" href="index.php?view=partial">Partial</a>
                        <a id="link_replaced" class="dropdown-item" href="index.php?view=replaced">Replaced</a>
                        <a id="link_submitted" class="dropdown-item" href="index.php?view=submitted">Submitted</a>
                        <a id="link_finalized" class="dropdown-item" href="index.php?view=finalized">Finalized</a>
                        <div class="dropdown-divider"></div>
                        <a id="link_all" class="dropdown-item" href="index.php?view=all">All Items</a>
                    </div>
                </li>
                <li id="link_submissions_li" class="nav-item">
                    <a id="link_submissions" class="nav-link" href="index.php?view=submissions">
                        Submissions <span class="badge badge-light ml-1" id="submissionCount"></span>
                    </a>
                </li>
            </ul>
            <button id="addItems" type="button" class="btn btn-sm btn-success bg-gradient-success">Add Items</button>
            <form class="form-inline my-2 my-lg-0">
                <div class="input-group">
                    <input 
                        class="form-control py-2 border-right-0 border"
                        type="Search" placeholder="Search" id="searchBox">
                    <span class="input-group-append">
                        <div class="input-group-text bg-white">
                            <i class="icon-search icon-14px"></i>
                        </div>
                    </span>
                </div>
            </form>
            <a href="https://github.com/RaekwonDaChef/Insurance-ItemTracker">
				<i id="githubLink" class="icon-github icon-24px icon-light" data-toggle="tooltip" data-placement="bottom" title="GitHub Project Page"></i>
			</a>
        </div>
    </nav>
    <div id="pageContainer">
        <div id="successMessage" class="alert alert-success alert-dismissible" role="alert">
            <span id="successMessageText"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div id="failMessage" class="alert alert-danger alert-dismissible" role="alert">
            <span id="failMessageText"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div id="searchCaption" class="alert alert-secondary" role="alert">
            <h4>Search Results</h4>
        </div>
        <header class="jumbotron text-center">
            <h1>Insurance Claim</h1>
            <p>Item Tracker</p> 
        </header>
        <div id="welcomeNoItems" class="jumbotron">
            <h1 class="display-4">Welcome!</h1>
            <p class="lead">Looks like no items have been added to the database yet...</p>
            <hr class="my-4">
            <p>Click on 'Add Items' below to get started.</p>
            <a id="addItemsWelcome" class="btn btn-primary btn-lg" href="#" role="button">Add Items</a>
        </div>
        <section id="container_stats" class="container-fluid show-content">
            <div class="row justify-content-md-center align-items-center">
                <div class="col-sm-1">
                </div>
                <div class="col-sm-5">
                    <canvas id="bar-chart"></canvas>
                </div>
                <div class="col-sm-5">
                    <canvas id="pie-chart"></canvas>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
        </section>
        <nav id="tableNav" class="navbar navbar-expand-sm bg-light justify-content-center">
            <ul class="nav nav-tabs navbar-nav">
                <li class="nav-item">
                    <a id="tableNav_notreplaced" class="nav-link" href="index.php?view=notreplaced">Not Replaced</a>
                </li>
                <li class="nav-item">
                    <a id="tableNav_partial" class="nav-link" href="index.php?view=partial">Partial</a>
                </li>
                <li class="nav-item">
                    <a id="tableNav_replaced" class="nav-link" href="index.php?view=replaced">Replaced</a>
                </li>
                <li class="nav-item">
                    <a id="tableNav_submitted" class="nav-link" href="index.php?view=submitted">Submitted</a>
                </li>
                <li class="nav-item">
                    <a id="tableNav_finalized" class="nav-link" href="index.php?view=finalized">Finalized</a>
                </li>
                <li class="nav-item">
                    <a id="tableNav_all" class="nav-link" href="index.php?view=all">All</a>
                </li>
            </ul>
        </nav>
        <div id="order_settings" class="d-flex flex-wrap flex-row-reverse">
            <div class="p-2">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="sortOrderMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Order: Asc. (Low to High)
                    </button>
                    <div class="dropdown-menu" aria-labelledby="sortOrderMenuButton">
                        <a onclick="ReSortTable('', 'asc')" class="dropdown-item" href="#">Asc. (Low to High)</a>
                        <a onclick="ReSortTable('', 'desc')" class="dropdown-item" href="#">Desc. (High To Low)</a>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="sortOrderByMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sort By: Description
                    </button>
                    <div class="dropdown-menu" aria-labelledby="sortOrderByMenuButton">
                        <a onclick="ReSortTable('item', '')" class="dropdown-item" href="#">Item #</a>
                        <a onclick="ReSortTable('description', '')" class="dropdown-item" href="#">Description</a>
                        <a onclick="ReSortTable('quantity', '')" class="dropdown-item" href="#">Quantity</a>
                        <a onclick="ReSortTable('unit_price', '')" class="dropdown-item" href="#">Price</a>
                        <a onclick="ReSortTable('collect_amount', '')" class="dropdown-item" href="#">$$</a>
                        <a onclick="ReSortTable('spend_amount', '')" class="dropdown-item" href="#">Spend</a>
                        <a onclick="ReSortTable('status', '')" class="dropdown-item" href="#">Status</a>
                    </div>
                </div>
            </div>
            <div class="p-2">
                <button id="submitReceipts" type="button" class="btn btn-dark btn-block bg-gradient-dark">
                    Submit Receipts <span id="submitCount" class="badge badge-light ml-1">0</span>
                </button>
            </div>
        </div>
        <section id="container_all" class="row justify-content-center"></section>
        <section id="container_notreplaced" class="row justify-content-center"></section>
        <section id="container_partial" class="row justify-content-center"></section>
        <section id="container_replaced" class="row justify-content-center"></section>
        <section id="container_submitted" class="row justify-content-center"></section>
        <section id="container_submissions" class="row justify-content-center"></section>
        <section id="container_finalized" class="row justify-content-center"></section>
        <section id="container_searchresults" class="row justify-content-center"></section>
        <div 
             class="modal fade" id="processReceipts" tabindex="-1" role="dialog"
             aria-labelledby="processReceiptsLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><span id="processReceiptsTitle">Title</span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="priority-none" id="processReceiptsTimestamp"></span>
                        <p><span id="processReceiptsText">Text</span></p>
                    </div>
                    <div class="modal-footer">
                        <button id="processReceiptsConfirm" type="button" class="btn btn-success">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div 
             class="modal fade" id="statusChangeConfirm" tabindex="-1" role="dialog"
             aria-labelledby="statusChangeConfirmLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to change <span id="statusChangeCount"></span> items from status '<span id="statusChangeCurrent"></span>' to '<span id="statusChangeNew"></span>'?</p>
                    </div>
                    <div class="modal-footer">
                        <button id="statusChangeConfirmGo" type="button" class="btn btn-success">Confirm</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div 
             class="modal fade" id="deleteItemsConfirm" tabindex="-1" role="dialog"
             aria-labelledby="deleteItemsConfirmLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete <span id="deleteItemCount"></span>?</p>
                        <p>WARNING: This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button id="deleteItemsConfirmGo" type="button" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div 
             class="modal fade" id="addItemsDialog" tabindex="-1" role="dialog"
             aria-labelledby="addItemsDialogLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="addItemSuccessMsg" class="alert alert-success alert-dismissible" role="alert">
                            Success! Added 1 item to database.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="addItemFailMsg" class="alert alert-danger alert-dismissible" role="alert">
                            Failed! Added 0 items to database.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div id="addItemErrors" class="alert alert-danger" role="alert">
                            <h6>Errors Found: <span id="addItemsErrorsCount"></span>!</h6>
                            <ul id="addItemsErrorList" class="list-group"></ul>
                        </div>
                        <form id="addItemForm">
                            <div class="form-group">
                                <label for="item">Item #</label>
                                <input name="item" type="number" class="form-control" id="addInputItem" placeholder="Item #...">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input name="description" type="text" class="form-control" id="addInputDescription" placeholder="Description...">
                                <small class="form-text text-muted">
                                    Maximum 32 characters.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input name="quantity" type="number" class="form-control" id="addInputQty" placeholder="Quantity...">
                            </div>
                            <div class="form-group">
                                <label for="unit_price">Unit Price</label>
                                <input name="unit_price" type="number" class="form-control" id="addInputunit_price" placeholder="Unit Price...">
                                <small class="form-text text-muted">
                                    Money amounts should not include a $ sign.
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="collect_amount">
                                    Lost Value (Depracation)
                                </label>
                                <input name="collect_amount" type="number" class="form-control" id="addInputRCV" placeholder="Enter RCV...">
                            </div>
                            <div class="form-group">
                                <label for="acv_paid">
                                    Actual Cash Value
                                </label>
                                <input name="acv_paid" type="number" class="form-control" id="addInputACV" placeholder="Enter ACV...">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button id="addItemSave" type="button" class="btn btn-success">
                            Save
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-gradient-secondary">
        <div class="d-flex flex-wrap align-items-end">
            <div class="mr-auto p-2">
                <i class="icon-check-square-o icon-16px"></i>
                <span id="selectedNumber">0</span>
            </div>
            <div class="p-0">
                <div class="col-1">
                    <div class="btn-group dropup">
                        <button
                                class="btn btn-secondary dropdown-toggle" type="button" id="statusDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Status: Not Replaced
                        </button>
                        <div class="dropdown-menu" aria-labelledby="statusDropdown">
                            <a id="statusChangeNotReplaced" class="dropdown-item" href="#">Not Replaced</a>
                            <a id="statusChangePartial" class="dropdown-item" href="#">Partial</a>
                            <a id="statusChangeReplaced" class="dropdown-item" href="#">Replaced</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-0">
                <div class="col-1">
                    <button id="deleteItems" type="button" class="btn btn-warning">Delete</button>
                </div>
            </div>
            <div class="p-2 bg-gradient-success">
                <div class="col-1">
                    $<span id="selectedCollect">0</span>
                </div>
            </div>
            <div class="p-2 bg-gradient-danger">
                <div class="col-1">
                    $<span id="selectedSpend">0</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>