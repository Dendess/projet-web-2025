<select name="{{ $name }}" id="{{ $name }}" class="form-select">
    <!-- L'option initiale qui contient le label -->
    <option value="">{{ $label }}</option>

    <!-- Boucle pour afficher les options dynamiques -->
    @foreach ($options as $key => $value)
        <!-- Chaque option a comme valeur la clé et affiche la valeur -->
        <option value="{{ $key }}">{{ $value }}</option>
    @endforeach
</select>
