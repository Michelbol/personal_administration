
<div class="{{ $col }}">
    <label for="{{ $id_bank }}">Banco</label>
    <select required="{{ $required }}" name="{{ $id_bank }}" id="{{ $id_bank }}" class="form-control select2"></select>
</div>

@section('scripts')
    <script>

        $("#{{ $id_bank }}").select2({
            ajax: {
                url: "{{ routeTenant('bank.index') }}",
                dataType: 'json',
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
// parse the results into the format expected by Select2
// since we are using custom formatting functions we do not need to
// alter the remote JSON data, except to indicate that infinite
// scrolling can be used
                    params.page = params.page || 1;

                    return {
                        results: data,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true
            },
            placeholder: 'Informe um banco',
            escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
            minimumInputLength: 0,
            templateResult: formatRepo,
            templateSelection: formatRepoSelection
        });

        function formatRepo (repo) {
            if (repo.loading) {
                return repo.text;
            }

            var markup = "<div class='select2-result-repository clearfix'>" +
                    "<div class='select2-result-repository__avatar'></div>" +
                    "<div class='select2-result-repository__meta'>" +
                    "<div class='select2-result-repository__title'>" + repo.number + "</div>";

            if (repo.description) {
                markup += "<div class='select2-result-repository__description'>" + repo.description + "</div>";
            }

            markup += "<div class='select2-result-repository__statistics'>" +
                    "<div class='select2-result-repository__forks'><i class='fa fa-flash'></i> " + repo.name + "</div>" +
//                    "<div class='select2-result-repository__stargazers'><i class='fa fa-star'></i> " + repo.stargazers_count + " Stars</div>" +
//                    "<div class='select2-result-repository__watchers'><i class='fa fa-eye'></i> " + repo.watchers_count + " Watchers</div>" +
                    "</div>" +
                    "</div></div>";

            return markup;
        }

        function formatRepoSelection (repo) {
            return repo.name || repo.text;
        }
        @if(isset($id_selected))
        if({{ $id_selected > 0 }}){
            var data = {
                id: {{ $id_selected }},
                text: "{{ $name_selected }}"
            };

            var newOption = new Option(data.text, data.id, false, false);
            $("#{{ $id_bank }}").append(newOption).trigger('change');
        }
        @endif
    </script>

@endsection