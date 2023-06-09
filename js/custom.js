jQuery(document).ready(function () {
  'use strict'

  // Tooltip
  jQuery('.tooltips').tooltip({ container: 'body' })

  // Popover
  jQuery('.popovers').popover()

  jQuery(function () {
    jQuery('.date').mask('99/99/9999')
    jQuery('.cpf').mask('999.999.999-99')
    jQuery('.cep').mask('99999-999')
    jQuery('.peso').mask('9.9')
    //jQuery('.carga-horaria').mask('00.0');
  })

  // Select2
  jQuery('.select-basic, .select-multi').select2()
  jQuery('.select-search-hide').select2({
    minimumResultsForSearch: -1
  })

  function format (item) {
    return (
      '<i class="fa ' +
      (item.element[0].getAttribute('rel') === undefined
        ? ''
        : item.element[0].getAttribute('rel')) +
      ' mr10"></i>' +
      item.text
    )
  }

  // This will empty first option in select to enable placeholder
  jQuery('select option:first-child').text('')

  jQuery('.select-templating').select2({
    formatResult: format,
    formatSelection: format,
    escapeMarkup: function (m) {
      return m
    }
  })

  // Show panel buttons when hovering panel heading
  jQuery('.panel-heading').hover(
    function () {
      jQuery(this).find('.panel-btns').fadeIn('fast')
    },
    function () {
      jQuery(this).find('.panel-btns').fadeOut('fast')
    }
  )

  // Close Panel
  jQuery('.panel .panel-close').click(function () {
    jQuery(this).closest('.panel').fadeOut(200)
    return false
  })

  // Minimize Panel
  jQuery('.panel .panel-minimize').click(function () {
    var t = jQuery(this)
    var p = t.closest('.panel')
    if (!jQuery(this).hasClass('maximize')) {
      p.find('.panel-body, .panel-footer').slideUp(200)
      t.addClass('maximize')
      t.find('i').removeClass('fa-minus').addClass('fa-plus')
      jQuery(this).attr('data-original-title', 'Maximize Panel').tooltip()
    } else {
      p.find('.panel-body, .panel-footer').slideDown(200)
      t.removeClass('maximize')
      t.find('i').removeClass('fa-plus').addClass('fa-minus')
      jQuery(this).attr('data-original-title', 'Minimize Panel').tooltip()
    }
    return false
  })

  jQuery('.leftpanel .nav .parent > a').click(function () {
    var coll = jQuery(this).parents('.collapsed').length

    if (!coll) {
      jQuery('.leftpanel .nav .parent-focus').each(function () {
        jQuery(this).find('.children').slideUp('fast')
        jQuery(this).removeClass('parent-focus')
      })

      var child = jQuery(this).parent().find('.children')
      if (!child.is(':visible')) {
        child.slideDown('fast')
        if (!child.parent().hasClass('active'))
          child.parent().addClass('parent-focus')
      } else {
        child.slideUp('fast')
        child.parent().removeClass('parent-focus')
      }
    }
    return false
  })

  // Menu Toggle
  jQuery('.menu-collapse').click(function () {
    if (!$('body').hasClass('hidden-left')) {
      if ($('.headerwrapper').hasClass('collapsed')) {
        $('.headerwrapper, .mainwrapper').removeClass('collapsed')
      } else {
        $('.headerwrapper, .mainwrapper').addClass('collapsed')
        $('.children').hide() // hide sub-menu if leave open
      }
    } else {
      if (!$('body').hasClass('show-left')) {
        $('body').addClass('show-left')
      } else {
        $('body').removeClass('show-left')
      }
    }
    return false
  })

  // Add class nav-hover to mene. Useful for viewing sub-menu
  jQuery('.leftpanel .nav li').hover(
    function () {
      $(this).addClass('nav-hover')
    },
    function () {
      $(this).removeClass('nav-hover')
    }
  )

  // For Media Queries
  jQuery(window).resize(function () {
    hideMenu()
  })

  hideMenu() // for loading/refreshing the page
  function hideMenu () {
    if ($('.header-right').css('position') == 'relative') {
      $('body').addClass('hidden-left')
      $('.headerwrapper, .mainwrapper').removeClass('collapsed')
    } else {
      $('body').removeClass('hidden-left')
    }

    // Seach form move to left
    /*
      if ($(window).width() <= 375) {
         if ($('.leftpanel .form-search').length == 0) {
            $('.form-search').insertAfter($('.profile-left'));
         }
      } else {
         if ($('.header-right .form-search').length == 0) {
            $('.form-search').insertBefore($('.btn-group-notification'));
         }
      }*/
  }

  collapsedMenu() // for loading/refreshing the page
  function collapsedMenu () {
    if ($('.logo').css('position') == 'relative') {
      $('.headerwrapper, .mainwrapper').addClass('collapsed')
    } else {
      $('.headerwrapper, .mainwrapper').removeClass('collapsed')
    }
  }

  //PAGEDITAR
  jQuery('.thmb').hover(
    function () {
      var t = jQuery(this)
      t.find('.ckbox').show()
      t.find('.fm-group').show()
    },
    function () {
      var t = jQuery(this)
      if (!t.closest('.thmb').hasClass('checked')) {
        t.find('.ckbox').hide()
        t.find('.fm-group').hide()
      }
    }
  )

  jQuery('.ckbox').each(function () {
    var t = jQuery(this)
    var parent = t.parent()
    if (t.find('input').is(':checked')) {
      t.show()
      parent.find('.fm-group').show()
      parent.addClass('checked')
    }
  })

  jQuery('.ckbox').click(function () {
    var t = jQuery(this)
    if (!t.find('input').is(':checked')) {
      t.closest('.thmb').removeClass('checked')
      enable_itemopt(false)
    } else {
      t.closest('.thmb').addClass('checked')
      enable_itemopt(true)
    }
  })

  jQuery('#selectAll').click(function () {
    if (jQuery(this).hasClass('btn-default')) {
      jQuery('.thmb').each(function () {
        jQuery(this).find('input').attr('checked', true)
        jQuery(this).addClass('checked')
        jQuery(this).find('.ckbox, .fm-group').show()
      })
      enable_itemopt(true)
      jQuery(this).removeClass('btn-default').addClass('btn-primary')
      jQuery(this).text('Select None')
    } else {
      jQuery('.thmb').each(function () {
        jQuery(this).find('input').attr('checked', false)
        jQuery(this).removeClass('checked')
        jQuery(this).find('.ckbox, .fm-group').hide()
      })
      enable_itemopt(false)
      jQuery(this).removeClass('btn-primary').addClass('btn-default')
      jQuery(this).text('Select All')
    }
  })

  function enable_itemopt (enable) {
    if (enable) {
      jQuery('.media-options .btn.disabled')
        .removeClass('disabled')
        .addClass('enabled')
    } else {
      // check all thumbs if no remaining checks
      // before we can disabled the options
      var ch = false
      jQuery('.thmb').each(function () {
        if (jQuery(this).hasClass('checked')) ch = true
      })

      if (!ch)
        jQuery('.media-options .btn.enabled')
          .removeClass('enabled')
          .addClass('disabled')
    }
  }

  jQuery("a[data-rel^='prettyPhoto']").prettyPhoto()

  $('#downloadAll').click(function (event) {
    event.preventDefault()
    $('.thmb.checked .link-cupon').multiDownload()
  })

  var tovar_img_h = $('.dashboard-redirect').height()
  $('.dashboard-redirect').css('height', tovar_img_h)

  //DATATABLES
  jQuery('#list_clientes').DataTable({
    responsive: true,
    columnDefs: [{ orderable: false, targets: 1 }]
  })

  jQuery('#list_design').DataTable({
    responsive: true,
    columnDefs: [{ orderable: false, targets: 2 }]
  })

  jQuery('#list_tecnologia').DataTable({
    responsive: true,
    columnDefs: [{ orderable: false, targets: 2 }]
  })

  jQuery('#list_fotografia').DataTable({
    responsive: true,
    paging: false,
    info: false,
    processing: true,
    searching: false,

    columnDefs: [{ orderable: false, targets: 2 }]
  })

  jQuery('#list_arquitetura').DataTable({
    responsive: true,
    columnDefs: [{ orderable: false, targets: 2 }]
  })

  jQuery('#list_filme').DataTable({
    responsive: true,
    columnDefs: [{ orderable: false, targets: 2 }]
  })

  $('.checkbox-avaliacao').click(function () {
    var checked_status = this.checked
    if (checked_status == true) {
      $('.button-avaliacao').removeAttr('disabled')
    } else {
      $('.button-avaliacao').attr('disabled', 'disabled')
    }
  })

  $(document).ready(function () {
    $('#selecctall').click(function (event) {
      //on click
      if (this.checked) {
        // check select status
        $('.checkbox').each(function () {
          //loop through each checkbox
          this.checked = true //select all checkboxes with class "checkbox"
        })
      } else {
        $('.checkbox').each(function () {
          //loop through each checkbox
          this.checked = false //deselect all checkboxes with class "checkbox"
        })
      }
    })
  })

  $(document).ready(function () {
    $('.bs-example-modal-lg').modal({
      show: true
    })
  })

  $('.fancybox').click(function () {
    $.fancybox({
      type: 'html',
      autoSize: false,
      content:
        '<iframe src="http://docs.google.com/gview?url=' +
        this.href +
        '&embedded=true" height="99%" width="100%"></iframe>'
      /*beforeClose: function() {
        $(".fancybox").unwrap();
      }*/
    }) //fancybox
    return false
  }) //click
  $('#fone1').mask('(99) 99999-9999')
  // Datepicker

  jQuery('#datepicker').datepicker()

  $(function () {
    $('#datepicker').datepicker()
  })

  //DROPZONE
  /*$(function(){
      Dropzone.options.buffonDropzone = {
          //maxFilesize: 5,
          autoProcessQueue: true,
          //addRemoveLinks: true,
          dictResponseError: 'upload.php',
          acceptedFiles: ".pdf",
          init:function(){
              var self = this;

              $('#submit-all').click(function(){           
                   self.processQueue();
              });*/

  /*
              // config
              self.options.addRemoveLinks = true;
              self.options.dictRemoveFile = "Excluir";

              //New file added
              self.on("addedfile", function (file) {
                  console.log('new file added ', file);
              });

              // Send file starts
              self.on("sending", function (file) {
                  console.log('upload started', file);
                  $('.meter').show();
              });

              // File upload Progress
              self.on("totaluploadprogress", function (progress) {
                  console.log("progress ", progress);
                  $('.roller').width(progress + '%');
              });

              self.on("queuecomplete", function (progress) {
                  $('.meter').delay(999).slideUp(999);
                  //location.reload();
              });

              // On removing file
              self.on("removedfile", function (file) {
                  console.log(file);
              });

              self.on("success", function(file, responseText) {
                  window.location.href="dashboard.php";
              });*/
  //    }
  //  };
  //})
})
