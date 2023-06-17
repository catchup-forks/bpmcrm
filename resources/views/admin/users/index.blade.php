@extends('layouts.layout')

@section('title')
    {{__('Users')}}
@endsection

@section('mainbar')
    @include('layouts.sidebar', ['sidebar'=> Menu::get('sidebar_admin')])
@endsection

@section('content')
    <div class="container page-content" id="users-listing">
        <h1>{{__('Users')}}</h1>
        <div class="row">
            <div class="col">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                    <input v-model="filter" class="form-control" placeholder="{{__('Search')}}...">
                </div>

            </div>
            <div class="col-8" align="right">
                <button type="button" class="btn btn-action text-light" data-toggle="modal" data-target="#addUser">
                    <i class="fas fa-plus"></i>
                    {{__('User')}}</button>
            </div>
        </div>
        <div class="container-fluid">
            <users-listing ref="listing" :filter="filter" v-on:reload="reload"></users-listing>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="addUser">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Add A User')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        {!!Form::label('username', __('Username'))!!}
                        {!!Form::text('username', null, ['class'=> 'form-control', 'v-model'=> 'username', 'v-bind:class'
                        => '{\'form-control\':true, \'is-invalid\':addError.username}'])!!}
                        <div class="invalid-feedback" v-for="username in addError.username">@{{username}}</div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('firstname', __('First name'))!!}
                        {!!Form::text('firstname', null, ['class'=> 'form-control', 'v-model'=> 'firstname', 'v-bind:class'
                        => '{\'form-control\':true, \'is-invalid\':addError.firstname}'])!!}
                        <div class="invalid-feedback" v-for="firstname in addError.firstname">@{{firstname}}</div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('lastname', __('Last name'))!!}
                        {!!Form::text('lastname', null, ['class'=> 'form-control', 'v-model'=> 'lastname', 'v-bind:class'
                        => '{\'form-control\':true, \'is-invalid\':addError.lastname}'])!!}
                        <div class="invalid-feedback" v-for="lastname in addError.lastname">@{{lastname}}</div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('status', 'Status');!!}
                        {!!Form::select('size', ['ACTIVE' => 'Active', 'INACTIVE' => 'Inactive'], 'Active', ['class'=> 'form-control', 'v-model'=> 'status',
                        'v-bind:class' => '{\'form-control\':true, \'is-invalid\':addError.status}']);!!}
                        <div class="invalid-feedback" v-for="status in addError.status">@{{status}}</div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('email', __('Email'))!!}
                        {!!Form::email('email', null, ['class'=> 'form-control', 'v-model'=> 'email', 'v-bind:class' =>
                        '{\'form-control\':true, \'is-invalid\':addError.email}'])!!}
                        <div class="invalid-feedback" v-for="email in addError.email">@{{email}}</div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('password', __('Password'))!!}
                        {!!Form::password('password', ['class'=> 'form-control', 'v-model'=> 'password', 'v-bind:class' =>
                        '{\'form-control\':true, \'is-invalid\':addError.password}'])!!}
                        <div class="invalid-feedback" v-for="password in addError.password">@{{password}}</div>
                    </div>
                    <div class="form-group">
                        {!!Form::label('confpassword', __('Confirm Password'))!!}
                        {!!Form::password('confpassword', ['class'=> 'form-control', 'v-model'=> 'confpassword',
                        'v-bind:class' => '{\'form-control\':true, \'is-invalid\':addError.password}'])!!}

                    </div>
                    <div class="form-group">
                        {!!Form::label('groups', __('Groups'))!!}
                        <multiselect v-model="selectedGroups" :options="availableGroups" :multiple="true"
                                     track-by="name"
                                     :custom-label="customLabel" :show-labels="false" label="name">

                            <template slot="tag" slot-scope="props">
                            <span class="multiselect__tag  d-flex align-items-center" style="width:max-content;">
                                <span class="option__desc mr-1">@{{ props.option.name }}
                                    <span class="option__title">@{{ props.option.desc }}</span>
                                </span>
                                <i aria-hidden="true" tabindex="1" @click="props.remove(props.option)"
                                   class="multiselect__tag-icon"></i>
                            </span>
                            </template>

                            <template slot="option" slot-scope="props">
                                <div class="option__desc d-flex align-items-center">
                                    <span class="option__title mr-1">@{{ props.option.name }}</span>
                                    <span class="option__small">@{{ props.option.desc }}</span>
                                </div>
                            </template>
                        </multiselect>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary"
                            data-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" class="btn btn-secondary" @click="onSubmit"
                            id="disabledForNow">{{__('Save')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{mix('js/admin/users/index.js')}}"></script>

    <script>
        new Vue({
            el: '#addUser',
            data: {
                username: '',
                firstname: '',
                lastname: '',
                status: '',
                email: '',
                password: '',
                confpassword: '',
                addError: {},
                submitted: false,
                selectedGroups: [],
                availableGroups: @json($groups),
            },
            methods: {
                customLabel(options) {
                    return ` ${options.img} ${options.title} ${options.desc} `
                },
                validatePassword() {
                    if (this.password !== this.confpassword) {
                        this.addError.password = ['Passwords must match']
                        this.password = ''
                        this.submitted = false
                        return false
                    }
                    return true
                },
                onSubmit() {
                    this.submitted = true;
                    if (this.validatePassword()) {
                        ProcessMaker.apiClient.post("/users", {
                            username: this.username,
                            firstname: this.firstname,
                            lastname: this.lastname,
                            status: this.status,
                            email: this.email,
                            password: this.password
                        })
                            .then(response => {
                                ProcessMaker.alert('{{__('User successfully added ')}}', 'success');
                                const promises = [];
                                this.selectedGroups.forEach(group => {
                                    promises.push(new Promise(
                                        (resolve, reject) => {
                                            ProcessMaker.apiClient.post("/group_members", {
                                                member_type: "app\\Models\\User",
                                                member_id: response.data.id,
                                                group_id: group.id
                                            })
                                                .then(() => {
                                                    resolve(true)
                                                })
                                                .catch(() => {
                                                    ProcessMaker.alert('{{__('An error occurred while saving the Group')}}', 'danger');
                                                    resolve(false)
                                               })
                                        })
                                    )
                                });

                                Promise.all(promises)
                                    .then(() => {
                                        ProcessMaker.alert('{{__('Groups successfully added ')}}', 'success')
                                    })
                                    .catch(() => {
                                        ProcessMaker.alert('{{__('Error when saving Group ')}}', 'danger')
                                    })
                                    .finally(() => {
                                        window.location = "/admin/users/" + response.data.id + '/edit'
                                    })
                            })
                            .catch(error => {
                                if (error.response.status === 422) {
                                    this.addError = error.response.data.errors
                                }
                            })
                            .finally(() => {
                                this.submitted = false
                            })
                    }
                }
            }
        })
    </script>
@endsection
@section('css')
    <style>
        /* .multiselect__tag {
              background: #788793 !important;
            } */
        .multiselect__element span img {
            border-radius: 50%;
            height: 20px;
        }

        .multiselect__tags-wrap {
            display: flex !important;
        }

        .multiselect__tags-wrap img {
            height: 15px;
            border-radius: 50%;
        }

        .multiselect__tag-icon:after {
            color: white !important;
        }

        /* .multiselect__tag-icon:focus, .multiselect__tag-icon:hover {
               background: #788793 !important;
            } */
        .multiselect__option--highlight {
            background: #00bf9c !important;
        }

        .multiselect__option--selected.multiselect__option--highlight {
            background: #00bf9c !important;
        }

        .multiselect__tags {
            border: 1px solid #b6bfc6 !important;
            border-radius: 0.125em !important;
            height: calc(1.875rem + 2px) !important;
        }

        .multiselect__tag {
            background: #788793 !important;
        }

        .multiselect__tag-icon:after {
            color: white !important;
        }
    </style>
@endsection