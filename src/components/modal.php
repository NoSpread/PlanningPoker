<div class="modal fade" id="lastError" tabindex="-1" role="dialog" aria-labelledby="lastErrorCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lastErrorLongTitle">Something went wrong.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                foreach ($_SESSION['LASTERROR'] as $key => $value) {
                    print("<div>{$value}</div>" . PHP_EOL);
                }
                $_SESSION['LASTERROR'] = [];
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('#lastError').modal('show');
</script>