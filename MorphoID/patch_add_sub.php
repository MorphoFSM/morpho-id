<?php
$file = 'C:\laragon\www\MorphoID\resources\views\admin\specimens.blade.php';
$content = file_get_contents($file);

// 1. Inject the Add Sub button before the Edit button in the specimens table
$search1 = '<a href="{{ url(\'admin/specimen/edit/\' . $item->id) }}" class="btn-action btn-edit">Edit</a>';
$replace1 = '<button type="button" class="btn-action btn-add-sub" onclick="bukaModalAddSub({{ $item->id }})" style="color: #00F0FF; background: rgba(0,240,255,0.1); border: 1px solid rgba(0,240,255,0.3); border-radius: 6px; padding: 0.4rem 1rem; font-weight: 600; cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background=\'#00F0FF\'; this.style.color=\'#050914\';" onmouseout="this.style.background=\'rgba(0,240,255,0.1)\'; this.style.color=\'#00F0FF\';">Add Sub</button>
                                  <a href="{{ url(\'admin/specimen/edit/\' . $item->id) }}" class="btn-action btn-edit">Edit</a>';

if (strpos($content, 'bukaModalAddSub(') === false) {
    $content = str_replace($search1, $replace1, $content);
    
    // 2. Add the bukaModalAddSub JS function and update bukaModal()
    $search2 = 'function bukaModal() {';
    $replace2 = 'function bukaModal() {
            let selectElement = document.querySelector(\'select[name="specimen_parent_id"]\');
            if(selectElement) { selectElement.value = ""; }';
            
    $content = str_replace($search2, $replace2, $content);
    
    $search3 = 'function tutupModal() {';
    $replace3 = 'function bukaModalAddSub(parentId) {
            document.getElementById(\'modalBorang\').style.display = \'flex\';
            let selectElement = document.querySelector(\'select[name="specimen_parent_id"]\');
            if(selectElement) { selectElement.value = parentId; }
        }
        function tutupModal() {';
    $content = str_replace($search3, $replace3, $content);
    
    file_put_contents($file, $content);
    echo "Added Add Sub button successfully!";
} else {
    echo "Add Sub button already exists.";
}
