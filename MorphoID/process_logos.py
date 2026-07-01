import urllib.request
from PIL import Image
import os

def remove_white_bg(url, output_path, tolerance=240):
    print(f"Processing {url}...")
    urllib.request.urlretrieve(url, "temp.png")
    
    img = Image.open("temp.png")
    img = img.convert("RGBA")
    
    datas = img.getdata()
    newData = []
    
    for item in datas:
        # Check if the pixel is white or near white
        if item[0] >= tolerance and item[1] >= tolerance and item[2] >= tolerance:
            # Change all white (also shades of whites)
            # pixels to transparent
            newData.append((255, 255, 255, 0))
        else:
            newData.append(item)
            
    img.putdata(newData)
    img.save(output_path, "PNG")
    print(f"Saved to {output_path}")

os.makedirs(r"c:\laragon\www\MorphoID\public\images", exist_ok=True)

remove_white_bg(
    "https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/natey/FSM%20Logo.png",
    r"c:\laragon\www\MorphoID\public\images\fsm_logo_transparent.png"
)

remove_white_bg(
    "https://rghwatxwpjdrwcktsxbo.supabase.co/storage/v1/object/public/natey/Meta%20logo.png",
    r"c:\laragon\www\MorphoID\public\images\meta_logo_transparent.png"
)

print("Done")
