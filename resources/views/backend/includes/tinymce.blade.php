{{-- <script src="https://cdn.tiny.cloud/1/z25hnmy7c0fjm6yxm452nl34azrst052829ez8cf8nj15c8n/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script> --}}

<script src="https://cdn.tiny.cloud/1/ao5f5se566nfpzgpqgdbvue3z6d21a5x3jp9l8hrjw648rm5/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

{{-- <script>
    tinymce.init({
        selector: 'textarea.tinymce',

        image_class_list: [
        {title: 'img-responsive', value: 'img-responsive'},
        ],
        height: 500,
        setup: function (editor) {
            editor.on('init change', function () {
                editor.save();
            });
        },
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste imagetools"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image ",

        image_title: true,
        automatic_uploads: true,
        images_upload_url: '{{ route('tinymce.uploadImage') }}',
        file_picker_types: 'image',
        file_picker_callback: function(cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function() {
                var file = this.files[0];

                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), { title: file.name });
                };
            };
            input.click();
        }
    });
</script> --}}


<script>
tinymce.init({
  selector: 'textarea.tinymce',
  image_class_list: [{title: 'img-responsive', value: 'img-responsive'}],
  height: 500,
  setup: function(editor){
    editor.on('init change', function(){
      editor.save();
    });
  },


  plugins: [
    'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor', 'pagebreak',
    'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
    'table', 'emoticons', 'template', 'help'
  ],
  toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
    'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
    'forecolor backcolor emoticons | help',
  menu: {
    favs: { title: 'My Favorites', items: 'code visualaid | searchreplace | emoticons' }
  },
  menubar: 'favs file edit view insert format tools table help',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',


  // plugins: [
  //   "advlist autolink lists link image charmap print preview hr anchor pagebreak",
  //   "searchreplace wordcount visualblocks visualchars code fullscreen",
  //   "insertdatetime media nonbreaking save table directionality",
  //   "emoticons template paste textpattern"
  // ],
  // toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor | backcolor | fullscreen | preview | fontsizeselect| table",

  image_title: true,
  automatic_uploads: true,
  images_upload_url: "{{ route('tinymce.uploadImage') }}",
  file_picker_types: 'image',
  file_picker_callback: function(cb, value, meta) {
    var input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/*');
    input.onchange = function() {
      var file = this.files[0];
      var reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = function(){
        var id = 'blobid' + (new Date()).getTime();
        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
        var base64 = reader.result.split(',')[1];
        var blobInfo = blobCache.create(id, file, base64);
        blobCache.add(blobInfo);
        cb(blobInfo.blobUri(), { title: file.name });
      };
    };
    input.click();
  }
});
</script>


 {{-- <script>
  var editor_config = {
    path_absolute : "/",
    selector: 'textarea.tinymce',
    relative_urls: false,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table directionality",
      "emoticons template paste textpattern"
    ],
    table_default_attributes: {
      'border-width': '1px'
    },
    table_default_styles: {
      'border': 'solid', 'width': '100%', 'cell': 'solid'
    },
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | forecolor | backcolor | fullscreen | preview | fontsizeselect| table",
    file_picker_callback : function(callback, value, meta) {
      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
      if (meta.filetype == 'image') {
        cmsURL = cmsURL + "&type=Images";
      } else {
        cmsURL = cmsURL + "&type=Files";
      }

      tinyMCE.activeEditor.windowManager.openUrl({
        url : cmsURL,
        title : 'Filemanager',
        width : x * 0.8,
        height : y * 0.8,
        resizable : "yes",
        close_previous : "no",
        onMessage: (api, message) => {
          callback(message.content);
        }
      });
    }
  };

  tinymce.init(editor_config);
</script> --}}



  {{-- <script>

var uploader = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4',
        browse_button : 'bg_upload', // you can pass an id...
        container: document.getElementById('bg_container'), // ... or DOM Element itself
        url : "{{ url('/') }}/tinymce/upload-image?_token="+$('meta[name="csrf-token"]').attr('content'),
        unique_names : false,
        max_file_count: 1,
        multi_selection: false,
        flash_swf_url : "{{ URL::asset('admin/js/pluploader/Moxie.swf') }}",
        silverlight_xap_url : "{{ URL::asset('admin/js/pluploader/Moxie.xap') }}",
        filters : {
            max_file_size : '60mb'
        },
        init: {
            FileUploaded: function (up, file) {
                var upload_path = "{{ URL::asset('uploads') }}";
                var img = jQuery('<img alt="click to change image" src="'+upload_path+"/"+file.name+'" style="width:100%;">');
                jQuery("#bg_container").addClass("no-border");
                jQuery("#bg_upload").html(img);
            },
            FilesAdded: function(up, files) {
                jQuery("#progress_file").css("display","block");
                plupload.each(files, function(file) {
                    var img_type = file.name;
                    jQuery("#progress_file").append('<div id="' + file.id + '" >' + img_type + ' (' + plupload.formatSize(file.size) + ')<b></b></div>');
                });
                uploader.start();
            },
            UploadProgress: function(up, file) {
                jQuery("#"+file.id).find("b").html('<span>' + file.percent + "%</span>");
            },
            UploadComplete: function () {
                jQuery("#progress_file").html("");
                jQuery("#progress_file").css("display","none");
            },
            Error: function(up, err) {
                console.log("\nError #" + err.code + ": " + err.message);
                //document.getElementById('console').appendChild(document.createTextNode("\nError #" + err.code + ": " + err.message));
            }
        }
    });
    uploader.init();


  </script> --}}
