<div class='form-group form-tag{{ $errors->has('tags') ? ' is-invalid' : '' }}'>
    {!! Form::label('tags', $name) !!}
    <select name="tags[]" class="form-control m-select2 input-tags" multiple>
        <?php foreach ($availableTags as $tag): ?>
        <option value="{{ $tag->slug }}" {{ in_array($tag->slug, $tags) ? ' selected' : null }}>{{ $tag->name }}</option>
        <?php endforeach; ?>
    </select>
    {!! $errors->first('tags', '<span class="form-control-feedback">:message</span>') !!}
</div>

@push('js-stack')
<script>
    $( document ).ready(function() {
        $('.input-tags').select2({
            tags: true,
            createTag: function (params) {
                var term = $.trim(params.term);

                if (term === '') {
                    return null;
                }

                return {
                    id: slugify(term),
                    text: term,
                    newTag: true // add additional parameters
                }
            }
        }).on('select2:select', function (e) {
            var data = e.params.data;
            console.log(data);
            if (data.newTag !== undefined && data.newTag === true) {
                axios.post("{{ route('api.tag.create') }}", {name: data.text, namespace: "{{ str_replace('\\','/',$namespace) }}", slug: slugify($.trim(data.text))})
                    .then(function (rs) {
                        console.log(rs.data);
                        e.params.data = {id: rs.data.slug, text: rs.data.name};
                    })
                    .catch(function (error) {

                    });
            }
        });
    });
</script>
@endpush
