@extends('layouts.auth2')

@section('title', __('lang_v1.reset_password'))

@section('content')
    <div class="login-form col-md-12 col-xs-12 right-col-content">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('update-password') }}">
            {{ csrf_field() }}


            <div class="form-group has-feedback {{ $errors->has('old_password') ? ' has-error' : '' }}">
                <label for="oldPasswordInput" class="form-label">Old Password</label>
                <input name="old_password" type="password" class="form-control @error('old_password') is-invalid @enderror"
                    id="oldPasswordInput" placeholder="Old Password">
                @error('old_password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group has-feedback {{ $errors->has('new_password') ? ' has-error' : '' }}">
                <label for="newPasswordInput" class="form-label">New Password</label>
                <input name="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror"
                    id="newPasswordInput" placeholder="New Password">
                @error('new_password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="confirmNewPasswordInput" class="form-label">Confirm New Password</label>
                <input name="new_password_confirmation" type="password" class="form-control" id="confirmNewPasswordInput"
                    placeholder="Confirm New Password">
            </div>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Change Password</button>
                <!-- /.col -->
            </div>
        </form>
    </div>
@endsection
