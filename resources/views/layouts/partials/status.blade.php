@if (session()->has('status-success'))
    <div class="status">
        <div class="container">
            <div class="status-box is-success">
                <div class="status-box-text">
                    <i class="fa fa-info"></i>
                    {{ session()->get('status-success') }}
                </div>
            </div>
        </div>
    </div>
@endif

@if (session()->has('status-warning'))
    <div class="status">
        <div class="container">
            <div class="status-box is-warning">
                <div class="status-box-text">
                    <i class="fa fa-info"></i>
                    {{ session()->get('status-warning') }}
                </div>
            </div>
        </div>
    </div>
@endif

@if (session()->has('status-danger'))
    <div class="status">
        <div class="container">
            <div class="status-box is-danger">
                <div class="status-box-text">
                    <i class="fa fa-info"></i>
                    {{ session()->get('status-danger') }}
                </div>
            </div>
        </div>
    </div>
@endif