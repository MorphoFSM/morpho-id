# Goal Description

Enhance the Edit Database page to allow more comprehensive update and delete operations, transforming it into a robust editor. The current page only allows basic text updates and image replacement. We will add new capabilities to make it a one-stop-shop for managing a specimen.

## Proposed Changes

### esources/views/edit_specimen.blade.php

1. **Danger Zone (Delete Specimen)**
   - Add a "Danger Zone" section at the very bottom of the page.
   - Include a red Delete Specimen button that triggers a confirmation modal/popup.
   - Submits a DELETE request to /specimen/padam/{id} without needing to go back to the main management table.

2. **Remove Current Image Option**
   - Add a checkbox or a direct Remove Image button under the "Current Image" preview. 
   - When checked/clicked, the backend will delete the file from the storage and set the database image to a placeholder, without requiring the user to upload a new one.

3. **Interactive Tags (Anatomical Characteristics)**
   - Convert the single text input into a dynamic "Pill Tag" editor.
   - Users can type a tag, hit enter or click "Add", and it appears as a pill with an 'X' button to easily delete individual characteristics.

### esources/js/specimenmanage.js (or inline script)
   - Add JavaScript logic to handle the interactive tag pills (adding, removing, and syncing with a hidden input field before form submission).
   - Add confirmation logic for the Delete Specimen button.

### pp/Http/Controllers/SpecimenController.php
   - Modify the update function to check if the "Remove Image" flag is set. If true, delete the old image file and update the database record.

## User Review Required

> [!IMPORTANT]
> The Danger Zone will allow immediate permanent deletion of a specimen directly from its edit page. Do you want this deletion to redirect you back to the "Specimen Management" table after it succeeds?

> [!TIP]
> I can also add a "Child Specimens" table at the bottom of the edit page *if* this specimen is a Parent Category, allowing you to delete its sub-specimens right here. Would you like this feature as well?
