<div class="modal fade" id="reason">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title"><i class="fa fa-comment-o"></i> {{ trans('deployments.reason') }}</h4>
            </div>
            <form role="form" method="post" action="{{ route('projects.deploy', ['id' => $project->id]) }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <input type="hidden" name="project_id" value="{{ $project->id }}" />
                <div class="modal-body">
                    <div class="callout callout-danger">
                        <i class="icon fa fa-warning"></i> {{ trans('deployments.warning') }}
                    </div>

                    @if ($project->allow_other_branch && (count($branches) || count($tags)))
                    <div class="form-group">
                        <label for="deployment_source">{{ trans('deployments.source') }}</label>
                        <ul class="list-unstyled">
                            <li>
                                <div class="radio">
                                    <label for="deployment_source_default">
                                        <input type="radio" class="deployment-source" name="source" id="deployment_source_default" value="{{ $project->branch }}" checked /> {{ trans('deployments.default', [ 'branch' => $project->branch ]) }}
                                    </label>
                                </div>
                            </li>

                            @if (count($branches))
                            <li>
                                <div class="radio">
                                    <label for="deployment_source_branch">
                                        <input type="radio" class="deployment-source" name="source" id="deployment_source_branch" value="branch" /> {{ trans('deployments.different_branch') }}

                                        <div class="deployment-source-container">
                                            <select class="form-control deployment-source" name="source_branch" id="deployment_branch">
                                                @foreach ($branches as $branch)
                                                    <option value="{{ $branch }}">{{ $branch }}</option>
                                                @endforeach
                                            </select> <button type="button" class="btn btn-default btn-refresh-branches" data-project-id="{{ $project->id }}" title="{{ trans('deployments.refresh_branches') }}" id="refresh_branches"><i class="fa fa-refresh"></i></button>
                                        </div>
                                    </label>
                                </div>
                            </li>
                            @endif

                            @if (count($tags))
                            <li>
                                <div class="radio">
                                    <label for="deployment_source_tag">
                                        <input type="radio" class="deployment-source" name="source" id="deployment_source_tag" value="tag" /> {{ trans('deployments.tag') }}

                                        <div class="deployment-source-container">
                                            <select class="form-control deployment-source" name="source_tag" id="deployment_tag">
                                                @foreach ($tags as $tag)
                                                    <option value="{{ $tag }}">{{ $tag }}</option>
                                                @endforeach
                                            </select> <button type="button" class="btn btn-default btn-refresh-branches" data-project-id="{{ $project->id }}" title="{{ trans('deployments.refresh_tags') }}" id="refresh_tags"><i class="fa fa-refresh"></i></button>
                                        </div>
                                    </label>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                    <hr />
                    @endif
                    <div class="form-group">
                        <label for="deployment_reason">{{ trans('deployments.describe_reason') }}</label>
                        <textarea rows="10" id="deployment_reason" class="form-control" name="reason" placeholder="For example, Allows users to reset their password"></textarea>
                    </div>
                    @if (count($optional))
                    <div class="form-group">
                        <label for="command_servers">{{ trans('deployments.optional') }}</label>
                        <ul class="list-unstyled">
                            @foreach ($optional as $command)
                            <li>
                                <div class="checkbox">
                                    <label for="deployment_command_{{ $command->id }}">
                                        <input type="checkbox" class="deployment-command" name="optional[]" id="deployment_command_{{ $command->id }}" value="{{ $command->id }}" @if ($command->default_on === true) checked @endif /> {{ $command->name }}
                                    </label>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="project-name" value="{{ $project->name }}">
                    <input type="text" id="entered-project-name" placeholder="{{ trans('deployments.need_project_name') }}" class="form-control" style="width:50%;display:inline-block;margin-right: 10px;border-radius:3px;">
                    <button type="submit" id="deploy-submit-button"  class="btn btn-primary pull-right btn-save" disabled><i class="fa fa-save"></i> {{ trans('projects.deploy') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('javascript')
    <script type="text/javascript">
        var inputProjectName = document.getElementById('entered-project-name');

        inputProjectName.onkeyup = function () {
            if (inputProjectName.value.toUpperCase() === document.getElementById('project-name').value.toUpperCase()) {
                document.getElementById('deploy-submit-button').disabled = false;
                inputProjectName.disabled = true;
            }
        };
    </script>
@endpush
