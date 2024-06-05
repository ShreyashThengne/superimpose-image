from flask import Flask, render_template, request
from PIL import Image
from io import BytesIO
import base64

app = Flask(__name__)

art = Image.open("art.jpg")  # this is suppose to be taken from the system directly
art_height = 29
art_width = 22

user_image = ""


@app.route("/")
def start():
    return render_template("upload_image.html")


@app.route("/upload", methods = ['POST'])
def upload():
    if "image" not in request.files:
        print("\nNo file uploaded\n")
        return render_template("upload_image.html")
    
    file = request.files['image']  

    global user_image
    img_bytes = BytesIO(file.read())
    user_image = Image.open(img_bytes)

    if hasattr(user_image, '_getexif'):   # soemtimes the uploaded image's orientation changes when uploaded here, I do not why. This code handles this anamoly
        exif = user_image._getexif()
        
        if exif is not None:
            orientation = exif.get(0x0112)
            if orientation == 3:
                user_image = user_image.rotate(180, expand=True)
            elif orientation == 6:
                print("h")
                user_image = user_image.rotate(270, expand=True)
            elif orientation == 8:
                user_image = user_image.rotate(90, expand=True)
    
    return render_template("wall_size_input.html")


@app.route("/wall_size", methods = ['POST'])
def wall_crop():
    # In this I have just directly taken the position of wall, but in production, the coordinates are to be selected through drag-select on image
    # also the measure is from left and top   (Left Top Corner)

    global wall_position
    wall_position = {'l':float(request.form["left"]), 'r':float(request.form["right"]), 'u':float(request.form["top"]), 'b':float(request.form["bottom"])} 
    return render_template("get_dimensions.html")

    
@app.route("/get_dimensions", methods = ['GET', 'POST'])
def get_wall_dims():
    wall_width = int(request.form['Wall_Width'])
    wall_height = int(request.form['Wall_Height'])


    pix_unit_per_cm = (wall_position['r'] - wall_position['l']) / wall_width   # this will be used to calculate the relation between actual dimension and image pixels

    wall_height_pix = wall_height * pix_unit_per_cm
    wall_width_pix = wall_width * pix_unit_per_cm

    art_height_pix = art_height * pix_unit_per_cm
    art_width_pix = art_width * pix_unit_per_cm

    resized_art = art.resize((int(art_width_pix), int(art_height_pix)))   # so that the size of the image is relative to wall

    # this puts the art over the selected portion on the uploaded image
    user_image.paste(resized_art,
                    box = (int(wall_position['l'] + (wall_width_pix - art_width_pix)//2),
                            int(wall_position['u'] + (wall_height_pix - art_height_pix)//2))
                    )
    
    img_byte_array = BytesIO()
    
    user_image.save(img_byte_array, format = 'png')
    img_byte_array = img_byte_array.getvalue()

    img_base64 = base64.b64encode(img_byte_array).decode('utf-8')  # for viewing on webpage, encoding was needed
    return render_template("final_image_display.html", img = img_base64)


if __name__ == '__main__':
    app.run(debug = True)