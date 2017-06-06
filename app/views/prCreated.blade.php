@model dynamic
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">@Html.TitleForPage(T("Change Password").ToString()) </h3>
    </div>
    @using (Html.BeginFormAntiForgeryPost())
    {
    <div class="box-body">
        <p>@T("Use the form below to change your password.")</p>
        <p>@T("New passwords are required to be a minimum of {0} characters in length.", ViewData["PasswordLength"]) </p>
        @Html.ValidationSummary(T("Password change was unsuccessful. Please correct the errors and try again.").ToString())
        <div class="col-xs-4">
            <div class="form-group">
                <label for="currentPassword">@T("Current password:")</label>
                @Html.Password("currentPassword", null, new { placeholder = T("Current Password"), @class = "form-control", })
                @Html.ValidationMessage("currentPassword")
            </div>
            <div class="form-group">
                <label for="newPassword">@T("New password:")</label>
                @Html.Password("newPassword", null, new { placeholder = T("New Password"), @class = "form-control" })
                @Html.ValidationMessage("newPassword")
            </div>
            <div class="form-group">
                <label for="confirmPassword">@T("Confirm new password:")</label>
                @Html.Password("confirmPassword", null, new { placeholder = T("Confirm Password"), @class = "form-control" })
                @Html.ValidationMessage("confirmPassword")
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button class="btn btn-primary" type="submit">@T("Change Password")</button>
    </div>
    }
</div>