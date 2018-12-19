{if isset($smarty.session.notifications)}
    <div class="alert alert-{$color_notification} alert-dismissible fade show" role="alert">
        {$smarty.session.notifications.message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
{/if}
