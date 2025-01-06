jQuery(document).ready(function () {
    ImgUpload1();
  });

  function ImgUpload1() {
      // alert(1);
    $('.upload__inputfile3').trigger('each');
    var imgWrap = "";
    var imgArray = [];
    $('.upload__inputfile3').each(function () {
          // alert(doc_type);
          $(this).on('change', function (e) {

            imgWrap = $(this).closest('.upload__box3').find('.upload__img-wrap3');
            var maxLength = $(this).attr('data-max_length');

            var files = e.target.files;
            var filesArr = Array.prototype.slice.call(files);
            var iterator = 0;
            //  console.log(filesArr);
            filesArr.forEach(function (f, index) {
              //  console.log(f,index);
              // if (!f.type.match('image.*')) {
              //   return;
              // }
              if (imgArray.length > maxLength) {
                return false
              } else {
                var len = 0;
                for (var i = 0; i < imgArray.length; i++) {
                  if (imgArray[i] !== undefined) {
                    len++;
                  }
                }
                if (len > maxLength) {
                  return false;
                } else {
                  imgArray.push(f);

                  var reader = new FileReader();
                  reader.onload = function (e) {
                    var file_extension = f.name.split('.').pop();
                    var html = '';
                    var html1 = '';
                    if(typeof doc_type == 'undefined' || doc_type!='people_doc_type'){
                        //   html1 += "<select required name='document[]' class='form-select' required><option value='4'>Marketing & brand</option><option value='5'>Legal business documentation</option><option value='6'>Templates</option></select></div>";
                        html1 +=`<div class="col-lg-4">
                                    <select  name="document[]" class="form-select mb-2" aria-label="Default select example" required>
                                        <option value="" selected disabled>Select Document Type</option>
                                        <option value="4">Marketing & brand</option>
                                        <option value="5">Legal business documentation</option>
                                        <option value="6">Templates</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button class="btn btn-danger btn-sm upload__img-close3" type="submit"><i class="ph-trash"></i></button>
                                </div>
                            </div>`;
                    }else{
                        html1 += `<div class="col-lg-2">
                                      <button class="btn btn-danger btn-sm upload__img-close3" type="submit"><i class="ph-trash"></i></button>
                                  </div>
                              </div>`;
                    }
                    if(file_extension=='pdf'){
                        //   html += "<div class='upload__img-box'><div style='background-image: url(" + config.pdf + ");margin-bottom:10px' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div><input class='form-control mb-2' name='image_title[]' placeholder='Enter title' required>";
                        html +=  `<div class="col-lg-12 row">
                                        <div class="col-lg-2">
                                            <img src="${config.pdf}" class="rounded-pill" width="36" height="36" alt="">
                                        </div>
                                        <div class="col-lg-4">
                                            <input class="form-control mb-2" name="image_title[]" placeholder="Enter Title" required value="">
                                        </div>`;

                    } else if(file_extension=='doc' || file_extension=='docx') {
                        //   html += "<div class='upload__img-box'><div style='background-image: url(" + config.doc + ");margin-bottom:10px' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div><input class='form-control mb-2' name='image_title[]' placeholder='Enter title' required>";
                        html +=  `<div class="col-lg-12 row">
                                        <div class="col-lg-2">
                                            <img src="${config.doc}" class="rounded-pill" width="36" height="36" alt="">
                                        </div>
                                        <div class="col-lg-4">
                                            <input class="form-control mb-2" name="image_title[]" placeholder="Enter Title" required value="">
                                        </div>`;
                    } else {
                        //   html += "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ");margin-bottom:10px' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div><input class='form-control mb-2' name='image_title[]' placeholder='Enter title' required>";
                        html +=  `<div class="col-lg-12 row">
                                        <div class="col-lg-2">
                                            <img src="${e.target.result}" class="rounded-pill" width="36" height="36" alt="">
                                        </div>
                                        <div class="col-lg-4">
                                            <input class="form-control mb-2" name="image_title[]" placeholder="Enter Title" required value="">
                                        </div>`;
                    }

                    html +=html1;

                    imgWrap.append(html);
                    iterator++;
                  }
                  reader.readAsDataURL(f);
                }
              }
            });
          });


    });

    $('body').on('click', ".upload__img-close3", function (e) {
      var file = $(this).parent().data("file");
      for (var i = 0; i < imgArray.length; i++) {
        if (imgArray[i].name === file) {
          imgArray.splice(i, 1);
          break;
        }
      }
      $(this).parent().parent().remove();
    });
  }
