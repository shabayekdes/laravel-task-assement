<!-- Title -->
<div>
    <x-input-label for="title" :value="__('Title')"/>
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $task->title)"
                  required
                  autofocus/>
    <x-input-error :messages="$errors->get('title')" class="mt-2"/>
</div>
<div class="mt-4">
    <x-input-label for="users" :value="__('Assigned User')"/>
    <select id="users" name="assigned_to_id"
            class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
        <option>Select one</option>
    </select>
    <x-input-error :messages="$errors->get('assigned_to_id')" class="mt-2"/>
</div>
<!-- Description -->
<div class="mt-4">
    <x-input-label for="description" :value="__('Description')"/>
    <x-textarea id="description" rows="3" class="block mt-1 w-full"
                name="description">{{ old('description', $task->description) }}</x-textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2"/>
</div>
@csrf
