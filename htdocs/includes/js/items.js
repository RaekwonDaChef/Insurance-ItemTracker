/*
    Insurance: Item Tracker
    Copyright (C) 2020 Michael Cabot
*/

/*
    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <https://www.gnu.org/licenses/>.
*/

function submitReceipts() {
    $.ajax({
        url: 'actions/submit.php',
        type: 'GET',
        success: function(responseText) {
            if (responseText > 0) {
                reloadTable('submitted');
                navigateTo('link_submitted');
                reloadSite();
                $("#successMessageText").html("Success! Changed the status of "+responseText+" items to 'submitted'");
                $("#successMessage").show();
                hideSelected();
            } else {
                $("#failMessageText").html("Failed! Changed the status of "+responseText+" items to 'submitted'");
                $("#failMessage").show();
            }
        },
        error: function(xhr) {
            console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

function finalizeReceiptsConfirm(timestamp) {
    $("#processReceiptsTitle").html("Finalize Receipts");
    $("#processReceiptsTimestamp").html(timestamp);
    $.getJSON('includes/submission.json.php', { timestamp: timestamp }, function(data) {
        $("#processReceiptsText").html("Are you sure you want to change "+data.total+" items to status 'finalized'?");
     });
    $("#processReceipts").modal();
}

function finalizeReceipts(timestamp) {
    $.ajax({
        url: 'actions/finalize.php',
        type: 'POST',
        data: {'timestamp' : timestamp},
        success: function(responseText) {
            if (responseText > 0) {
                reloadTable('finalized');
                navigateTo('link_finalized');
                reloadSite();
                $("#successMessageText").html("Success! Changed the status of "+responseText+" items to 'finalized'.");
                $("#successMessage").show();
                hideSelected();
            } else {
                $("#failMessageText").html("Failed! Changed the status of "+responseText+" items to 'finalized'");
                $("#failMessage").show();
            }
        },
        error: function(xhr) {
            console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
        }
    });
}

function hideSelected() {
    $("footer").fadeOut("slow");
    $("#selectedCollect").html("0");
    $("#selectedSpend").html("0");
    var height = $(window).height() - 55;
    $("#pageContainer").height(height);
    $(":checkbox").prop("checked", false);
}

function validateFormData() {
    var addItemNumber = parseInt($("#addInputItem").val());
    var errorsFound = 0;
    if (!Number.isInteger(addItemNumber)) {
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Item # is not a valid number.</li>');
    }
    if ($("#addInputDescription").val().length < 2) {
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Description must be longer than 1 character.</li>');
    }
    if ($("#addInputDescription").val().length > 32) {
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Description must be less than 32 characters long.</li>');
    }
    if (!Number.isInteger(parseInt($("#addInputQty").val()))) {
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Quantity is not a valid number.</li>');
    } else if (parseInt($("#addInputQty").val()) < 0){
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Quantity must be greater than 0.</li>');
    }
    if (isNaN(parseFloat($("#addInputunit_price").val()))) {
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Unit price is not a valid number.</li>');
    } else if (parseInt($("#addInputQty").val()) < 0) {
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Unit price must be greater than 0.</li>');
    }
    if (isNaN(parseFloat($("#addInputRCV").val()))) {
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Lost value is not a valid number.</li>');
    } else if (parseInt($("#addInputQty").val()) < 0){
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Lost value must be greater than 0.</li>');
    }
    if (isNaN(parseFloat($("#addInputACV").val()))) {
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Actual cash value is not a valid number.</li>');
    } else if (parseInt($("#addInputQty").val()) < 0){
        errorsFound++; //alert(errorsFound);
        $("#addItemsErrorList").append('<li class="list-group-item">Actual cash value must be greater than 0.</li>');
    }
    return errorsFound;
}

$(document).ready(function() {
    $.getJSON('output/submissions.php', { pending: "show" }, function(data) {
        if (data.all < 1) {
            $("#link_submissions").hide();
        } else {
            if (data.pending > 0) {
                $("#submissionCount").html(data.pending);
            } else {
                $("#submissionCount").hide();
            }
        }
    });
    $("#finalizeReceipts").click(function() {
        $("#processReceiptsTitle").html("Finalize Receipts");
        $.getJSON('includes/items.json.php', { type: "stats" }, function(data) {
            $("#processReceiptsText").html("Are you sure you want to change "+data.submitted.total+" items to status 'finalized'?");
        });
        $("#processReceipts").modal();
    });
    $("#submitReceipts").click(function() {
        $("#processReceiptsTitle").html("Submit Receipts");
        $.getJSON('includes/items.json.php', { type: "stats" }, function(data) {
            $("#processReceiptsText").html("Are you sure you want to change "+data.replaced.total+" items to status 'submitted'?");
        });
        $("#processReceipts").modal();
    });
    $("#processReceiptsConfirm").click(function() {
        $("#processReceipts").modal("hide");
        var modalTitle = $("#processReceiptsTitle").html();
        if (modalTitle == "Submit Receipts") {
            submitReceipts();
        } else if (modalTitle == "Finalize Receipts") {
            var timestamp = $("#processReceiptsTimestamp").html();
            finalizeReceipts(timestamp);
        }
    });
    $("#addItems, #addItemsWelcome").click(function() {
        $("#addItemsErrorList").empty();
        $("#addItemsDialog").modal();
    });
    $("#deleteItems").click(function() {
        var checkedTotal = $("input:checked").length;
        if (checkedTotal > 1) {
            $("#deleteItemCount").html(checkedTotal+" items");
        } else {
            $("#deleteItemCount").html(checkedTotal+" item");
        }
        $("#deleteItemsConfirm").modal();
    });
    
    $(document).on('change', '.selectAll', function() { 
        var selCol = 0.00;
        var selSpend = 0.00;
        var tableName = $(this).closest('table').attr('id');
        tableName = tableName.slice(6);
        var checkedTotal = 0;
        var classString = '';
        
        if ($(this).is(":checked")) {
            $("#pageContainer").height($(window).height() - 95); // resize window accordingly
            $("footer").fadeIn("slow");
            
            classString = '.item_row_';
            classString = classString.concat(tableName);
            $(classString).each(function() { $(this).addClass("selected"); });
            
            classString = '.item_checkbox_';
            classString = classString.concat(tableName);
            
            $(classString).each(function () {
                $(this).prop('checked',true);
                checkedTotal++;
                var itemNumber = $(this).parent().text().trim();
                $.getJSON('includes/items.json.php', { i: itemNumber }, function(data) {
                    selCol += parseFloat(data.collect_amount);
                    selSpend += parseFloat(data.spend_amount);
                    $("#selectedCollect").html(selCol.toFixed(2));
                    $("#selectedSpend").html(selSpend.toFixed(2));
                });
            });
            $("#selectedNumber").html(checkedTotal);
        } else {
            classString = '.item_checkbox_';
            classString = classString.concat(tableName);
            
            $(classString).each(function () {
                $(this).prop('checked',false);
            });
            $(".selected").removeClass("selected");
            $("#selectedCollect").html("0.00");
            $("#selectedSpend").html("0.00");
            $("footer").fadeOut("slow");
            $("#pageContainer").height($(window).height() - 50); // resize window accordingly
        }
    });
    
    $("#deleteItemsConfirmGo").click(function() {
        $("#deleteItemsConfirmGo").attr("disabled", true);
        $("#deleteItemsConfirmGo")
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...');
        var itemDeleteData = [];
        $("input:checked").each(function () {
            var itemNumber = $(this).parent().text().trim();
            itemDeleteData.push([itemNumber]);
        });
        itemDeleteData = JSON.stringify(itemDeleteData);
        $.ajax({
            url: 'actions/delete.php',
            type: 'POST',
            data: {data: itemDeleteData},
            success: function(responseText) {
                if (responseText > 0) {
                    if (responseText > 1) {
                        $("#successMessageText").html("Success! Deleted "+responseText+" items.");
                    } else {
                        $("#successMessageText").html("Success! Deleted "+responseText+" item.");
                    }
                    $("#successMessage").show();
                    reloadSite();
                    switch ($.urlParam('view')) { // reload table if user is viewing one
                        case "all": reloadTable('all'); break;
                        case "partial": reloadTable('partial'); break;
                        case "finalized": reloadTable('finalized'); break;
                        case "notreplaced": reloadTable('notreplaced'); break;
                        case "replaced": reloadTable('replaced'); break;
                        case "submitted": reloadTable('submitted'); break;
                    }
                    $("#deleteItemsConfirmGo").attr("disabled", false);
                    $("#deleteItemsConfirmGo").html('Delete');
                    $("#deleteItemsConfirm").modal("hide");
                } else {
                    $("#failMessageText").html("Failed! Deleted "+responseText+" items.");
                    $("#failMessage").show();
                    $("#deleteItemsConfirm").modal("hide");
                }
                hideSelected();
            },
            error: function(xhr) {
                console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
            }
        });
    });
    $("#addItemSave").click(function() {
        // validate form data first
        $("#addItemsErrorList").empty();
        $("#addItemSave").attr("disabled", true);
        $("#addItemSave").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
        var addItemNumber = parseInt($("#addInputItem").val());
        validateFormData();
        var errorsFound = $('#addItemErrors li').length; //alert(errorsFound);
        if (errorsFound > 0) {
            $("#addItemErrors").show();
            $("#addItemsErrorsCount").html(errorsFound);
            $("#addItemSave").attr("disabled", false);
            $("#addItemSave").html('Save');
        } else {
            $("#addItemErrors").hide();
            // ok to save item to database at this point
            $.ajax({
                url: 'actions/add.php',
                type: 'POST',
                data: $("#addItemForm").serialize(),
                success: function(responseText) {
                    if (responseText == 1) {
                        $("#addItemSuccessMsg").show();
                        $("#addItemForm").trigger("reset");
                        reloadSite();
                        if ($.urlParam('view') == "notreplaced") { reloadTable('notreplaced'); }
                    } else {
                        $("#addItemFailMsg").html(responseText);
                        $("#addItemFailMsg").show();
                    }
                    $("#addItemSave").attr("disabled", false);
                    $("#addItemSave").html('Save');
                },
                error: function(xhr) {
                    console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
                    $("#addItemSave").attr("disabled", false);
                    $("#addItemSave").html('Save');
                }
            });
        }
    });
    $(document).on("change", ".table_item", function () { // event when item is selected in table (checkbox)
        var itemNumber = $(this).parent().text().trim();
        var tableName = $(this).closest('table').attr('id');
        tableName = tableName.slice(6);
        var classString = '.item_checkbox_';
        var checkedTotal = 0;
        classString = classString.concat(tableName);
        $(classString).each(function () {
             if ($(this).prop("checked") == true) {
                 checkedTotal++;
             }
        });
        if (checkedTotal < 1) { // if no items are selected (user deselected all)
            $("footer").fadeOut("slow"); // hide footer
            $("#pageContainer").height($(window).height() - 50); // resize window accordingly
        } else if (checkedTotal == 1) {
            $("footer").fadeIn("slow"); // show footer
            $("#pageContainer").height($(window).height() - 95); // resize window accordingly
        }
        $("#selectedNumber").html(checkedTotal);
        $(this).closest("tr").toggleClass("selected", this.checked);
        var selCol = parseFloat($("#selectedCollect").html());
        var selSpend = parseFloat($("#selectedSpend").html());

        if ($(this).prop("checked") == true) {
            $.getJSON('includes/items.json.php', { i: itemNumber }, function(data) {
                selCol += parseFloat(data.collect_amount);
                selSpend += parseFloat(data.spend_amount);
                $("#selectedCollect").html(selCol.toFixed(2));
                $("#selectedSpend").html(selSpend.toFixed(2));
            });
        } else if ($(this).prop("checked") == false) {
            $(".selectAll").prop("checked", false);
            $.getJSON('includes/items.json.php', { i: itemNumber }, function(data) {
                selCol -= parseFloat(data.collect_amount);
                selSpend -= parseFloat(data.spend_amount);
                $("#selectedCollect").html(selCol.toFixed(2));
                $("#selectedSpend").html(selSpend.toFixed(2));
            });
        }
    });
    $("#statusChangeNotReplaced, #statusChangePartial, #statusChangeReplaced").click(function(event) {
        event.preventDefault();
        //alert(event.target.id);
        var checkedTotal = $("input:checked").length;
        var currentStatus = 0;
        var newStatus = 0;
        if (checkedTotal > 0) { // error check
            $("#statusChangeCount").html(checkedTotal);
            switch ($.urlParam('view')) {
                case "notreplaced": currentStatus = "Not Replaced"; break;
                case "partial": currentStatus = "Partial"; break;
                case "replaced": currentStatus = "Replaced"; break;
            }
            switch (event.target.id) {
                case "statusChangeNotReplaced": newStatus = "Not Replaced"; break;
                case "statusChangePartial": newStatus = "Partial"; break;
                case "statusChangeReplaced": newStatus = "Replaced"; break;
            }
            $("#statusChangeCurrent").html(currentStatus);
            $("#statusChangeNew").html(newStatus);
            // modal handles things from here
            $("#statusChangeConfirm").modal();
        }
    });
    $("#statusChangeConfirmGo").click(function(event) {
        event.preventDefault();
        $("#statusChangeConfirmGo").attr("disabled", true);
        $("#statusChangeConfirmGo")
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
        var itemStatusChangeData = [];
        var newStatus = 0;
        // make sure submit and finalize actions also hide the footer
        switch ($("#statusChangeNew").html()) {
            case "Not Replaced": newStatus = 1; break;
            case "Partial": newStatus = 2; break;
            case "Replaced": newStatus = 3; break;
        }
        
        $("input:checked").each(function () {
            var itemNumber = $(this).parent().text().trim();
            itemStatusChangeData.push([itemNumber, newStatus]);
        });
        itemStatusChangeData = JSON.stringify(itemStatusChangeData);
        $.ajax({
            url: 'actions/status.php',
            type: 'POST',
            data: {data: itemStatusChangeData},
            success: function(responseText) {
                if (responseText > 0) {
                    // responseText is the number of records updated
                    // show success message here
                    switch (newStatus) {
                        case '1': reloadTable('notreplaced'); break;
                        case '2': reloadTable('partial'); break;
                        case '3': reloadTable('replaced'); break;
                    }
                    reloadSite();
                    $("#successMessageText").html("Success! Changed the status of "+responseText+
                                              " items to '" + $("#statusChangeNew").html() + "'");
                    $("#successMessage").show();
                    $("#statusChangeConfirmGo").attr("disabled", false);
                    $("#statusChangeConfirmGo").html('Confirm');
                } else {
                    $("#failMessageText").html("Failed! "+responseText);
                    $("#failMessage").show();
                    $("tr").removeClass("selected");
                    $("#statusChangeConfirmGo").attr("disabled", false);
                    $("#statusChangeConfirmGo").html('Confirm');
                }
            },
            error: function(xhr) {
                console.log("AJAX Call Error: " + xhr.status + " " + xhr.statusText);
            }
        });
        hideSelected();
        $("#statusChangeConfirm").modal("hide"); // hide modal
    });
});