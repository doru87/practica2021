//CUSTOM JS
$("#userEditModal").on("shown.bs.modal", function (event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let user = button.data("user");

    let modal = $(this);

    modal.find("#userEditId").val(user.id);
    modal.find("#userEditName").text(user.name);
    modal.find("#userEditRole").val(user.role);
});

$("#changeBoard").on("change", function () {
    let id = $(this).val();
    window.location.href = "/board/" + id;
});
/***************************************************/
/***************************************************/
$("#userEditModalAjax").on("shown.bs.modal", function (event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let user = button.data("user");

    let modal = $(this);

    modal.find("#userEditIdAjax").val(user.id);
    modal.find("#userEditNameAjax").text(user.name);
    modal.find("#userEditRoleAjax").val(user.role);
});

$("#userEditButtonAjax").on("click", function () {
    $("#userEditAlert").addClass("hidden");

    let id = $("#userEditIdAjax").val();
    let role = $("#userEditRoleAjax").val();

    $.ajax({
        method: "POST",
        url: "/user-update/" + id,
        data: { role: role },
    }).done(function (response) {
        if (response.error !== "") {
            $("#userEditAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
$("#userDeleteModal").on("shown.bs.modal", function (event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let user = button.data("user");

    let modal = $(this);

    modal.find("#userDeleteId").val(user.id);
    modal.find("#userDeleteName").text(user.name);
});

$("#userDeleteButton").on("click", function () {
    $("#userDeleteAlert").addClass("hidden");
    let id = $("#userDeleteId").val();

    $.ajax({
        method: "POST",
        url: "/user/delete/" + id,
    }).done(function (response) {
        if (response.error !== "") {
            $("#userDeleteAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
const removedUsers = [];
$("#boardEditModal").on("shown.bs.modal", function (event) {
    let button = $(event.relatedTarget);

    let modal = $(this);

    let board = button.data("board");
    modal.find("#boardEditId").val(board.id);
    modal.find("#boardEditName").val(board.name);
    let users = button.data("users");

    $("#usersAssigned").val(users);
    $("#usersAssigned").trigger("change");

    document
        .querySelectorAll(".select2-selection__choice")
        .forEach(function (element) {
            element.addEventListener("click", function (event) {
                var userTitle = element.getAttribute("title");
                removedUsers.push(userTitle);
            });
        });
});

$("#boardEditButton").on("click", function () {
    let id = $("#boardEditId").val();
    let name = $("#boardEditName").val();
    var users = [];
    $("#usersAssigned").each(function () {
        users.push($(this).val());
    });
    users = Array.prototype.concat.apply([], users);

    $.ajax({
        method: "POST",
        url: "/board/update/" + id,
        data: {
            name: name,
            users: users,
            removedUsers: removedUsers,
        },
    }).done(function (response) {
        if (response.error !== "") {
            $("#boardEditAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
$("#boardDeleteModal").on("shown.bs.modal", function (event) {
    let button = $(event.relatedTarget);
    let board = button.data("board");

    let modal = $(this);

    modal.find("#boardDeleteId").val(board.id);
    modal.find("#boardDeleteName").text(board.name);
});

$("#boardDeleteButton").on("click", function () {
    let id = $("#boardDeleteId").val();

    $.ajax({
        method: "POST",
        url: "/board/delete/" + id,
    }).done(function (response) {
        if (response.error !== "") {
            $("#boardDeleteAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
$(".taskAssignment").on("click", function (event) {
    const data1 = $(this).attr("data-task");
    const task = JSON.parse(data1);
    $("#boardEditId").val(task.board_id);
    $("#taskEditId").val(task.id);

    const data2 = $(this).attr("data-user");
    const user = JSON.parse(data2);

    $("#userAssigned").val(user);

    $("#userAssigned").trigger("change");

    $("#taskAssignmentModal").modal("show");
});

$("#taskAssignmentButton").on("click", function () {
    let id = $("#taskEditId").val();
    let userAssignedId = $("#userAssigned").val();

    $.ajax({
        method: "POST",
        url: "/task/assignment/update/" + id,
        data: { userAssignedId: userAssignedId },
    }).done(function (response) {
        if (response.error !== "") {
            $("#taskEditAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
$(".taskName").on("click", function (event) {
    const data = $(this).attr("data-task");
    const task = JSON.parse(data);

    $("#taskEditName").val(task.name);
    $("#taskEditId").val(task.id);
    $("#taskNameModal").modal("show");
});

$("#taskNameButton").on("click", function () {
    let id = $("#taskEditId").val();
    let taskEditName = $("#taskEditName").val();

    $.ajax({
        method: "POST",
        url: "/task/name/update/" + id,
        data: { taskEditName: taskEditName },
    }).done(function (response) {
        if (response.error !== "") {
            $("#taskEditAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
$(".taskDescription").on("click", function (event) {
    const data = $(this).attr("data-task");
    const task = JSON.parse(data);

    $("#taskEditId").val(task.id);
    $("#taskEditDescription").val(task.description);
    $("#taskDescriptionModal").modal("show");
});

$("#taskDescriptionButton").on("click", function () {
    let id = $("#taskEditId").val();
    let taskEditDescription = $("#taskEditDescription").val();

    $.ajax({
        method: "POST",
        url: "/task/description/update/" + id,
        data: { taskEditDescription: taskEditDescription },
    }).done(function (response) {
        if (response.error !== "") {
            $("#taskEditAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
$(".taskStatus").on("click", function (event) {
    const data1 = $(this).attr("data-task");
    const task = JSON.parse(data1);

    $("#status").val(task.status);
    $("#taskEditId").val(task.id);
    $("#status").trigger("change");
    $("#taskStatusModel").modal("show");
});

$("#taskStatusButton").on("click", function () {
    let id = $("#taskEditId").val();
    let statusId = $("#status").val();

    $.ajax({
        method: "POST",
        url: "/task/status/update/" + id,
        data: { statusId: statusId },
    }).done(function (response) {
        if (response.error !== "") {
            $("#taskEditAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
$(".taskDateCreation").on("click", function (event) {
    const data1 = $(this).attr("data-task");
    const task = JSON.parse(data1);

    $("#taskEditId").val(task.id);
    var $startDate = $("#datetimepicker");
    $startDate.datetimepicker({
        value: task.created_at,
    });

    $startDate.on("change", function () {
        var selectedDate = $("#datetimepicker").val();
        $("#selectedDateTime").val(selectedDate);
    });

    $("#taskDateCreationModel").modal("show");
});

$("#taskDateCreationButton").on("click", function () {
    let id = $("#taskEditId").val();
    let selectedDateTime = $("#selectedDateTime").val();
    finalSelectedDateTime = selectedDateTime + ":00";

    $.ajax({
        method: "POST",
        url: "/task/datecreation/update/" + id,
        data: { selectedDateTime: finalSelectedDateTime },
    }).done(function (response) {
        if (response.error !== "") {
            $("#taskEditAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
$("#taskDeleteModal").on("shown.bs.modal", function (event) {
    let button = $(event.relatedTarget); // Button that triggered the modal
    let task = button.data("task");

    let modal = $(this);

    modal.find("#taskDeleteId").val(task.id);
    modal.find("#taskDeleteName").text(task.name);
});

$("#taskDeleteButton").on("click", function () {
    let id = $("#taskDeleteId").val();

    $.ajax({
        method: "POST",
        url: "/task/delete/" + id,
    }).done(function (response) {
        if (response.error !== "") {
            $("#taskDeleteAlert").text(response.error).removeClass("hidden");
        } else {
            window.location.reload();
        }
    });
});
/***************************************************/
/***************************************************/
$(".select2").select2();

$(".select2bs4").select2({
    theme: "bootstrap4",
});

$('[data-bs-toggle="tooltip"]').tooltip();
