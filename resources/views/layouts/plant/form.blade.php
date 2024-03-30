<form action="{{ $action }}" method="POST">
  @csrf

  <div class="form-floating mb-3">
    <input class="form-control" type="text" id="name" name="name" placeholder=" " required autofocus
      @if ($plant) value="{{ $plant->name }}" @endif>
    <label class="form-label" for="name">Name</label>
  </div>

  <div class="form-floating mb-3">
    <select class="form-control select2" id="strain_id" name="strain_id" required>
      <option value="">Select</option>
      @foreach ($strains as $strain)
        <option value="{{ $strain->id }}" {{ $plant && $plant->strain->id === $strain->id ? 'selected' : '' }}>
          {{ $strain->name }}
        </option>
      @endforeach
    </select>
    <label class="form-label" for="tags">Strain</label>
  </div>

  <div class="form-floating mb-3">
    <select class="form-control select2" id="tags" name="tags[]" multiple>
      @foreach ($tags as $tag)
        <option value="{{ $tag->id }}" {{ $plant && $plant->tags->contains('id', $tag->id) ? 'selected' : '' }}>
          {{ $tag->name }}
        </option>
      @endforeach
    </select>
    <label class="form-label" for="tags">Tags</label>
  </div>

  <hr />

  <h3 class="text-center"><i class="fas fa-sitemap me-2"></i>Properties</h3>

  <div id="strain-properties">

    <div class="row-property row-clone d-none mb-3">

      <hr />

      <div class="row">

        <div class="col">

          <div class="form-floating">
            <select class="form-control" id="properties-uid" name="properties[uid]">
              <option value="">Select</option>
              @foreach ($properties as $property)
                <option value="{{ $property->id }}">{{ $property->name }}</option>
              @endforeach
            </select>
            <label class="form-label" for="properties-uid">Properties</label>
          </div>

        </div>

        <div class="col">
          <div class="form-floating">
            <input class="form-control" type="text" id="values-uid" name="values[uid]" placeholder=" ">
            <label class="form-label" for="values-uid">Value</label>
          </div>
        </div>

      </div>
    </div>

    @if ($plant && $plant->properties)
      @foreach ($plant->properties as $plantProperty)
        <div class="row-property mb-3">

          <hr />

          <div class="row">

            <div class="col">

              <div class="form-floating">
                <select class="form-control select2" id="properties-{{ $uuid = Str::uuid() }}"
                  name="properties[{{ $uuid }}]">
                  <option value="">Select</option>
                  @foreach ($properties as $property)
                    <option value="{{ $property->id }}"
                      {{ $plantProperty->pivot->property_id === $property->id ? 'selected' : '' }}>
                      {{ $property->name }}
                    </option>
                  @endforeach
                </select>
                <label class="form-label" for="properties-{{ $uuid }}">Properties</label>
              </div>

            </div>

            <div class="col">

              <div class="form-floating">
                <input class="form-control" type="text" id="values-{{ $uuid }}"
                  name="values[{{ $uuid }}]" placeholder=" " value="{{ $plantProperty->pivot->value }}">
                <label class="form-label" for="values-{{ $uuid }}">Value</label>
              </div>

            </div>

          </div>

        </div>
      @endforeach
    @endif

  </div>

  <hr />

  <div class="d-flex">
    <button class="btn btn-outline-secondary btn-add-row" type="button" data-row-container="#strain-properties">
      <i class="fas fa-sitemap me-2"></i>Add property</button>
    <button class="btn btn-outline-success ms-auto" type="submit"><i class="far fa-save me-2"></i>Save</button>
  </div>
</form>
