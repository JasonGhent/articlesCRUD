(function() {
  var hideAll, recentLinkAction, showDetails;

  hideAll = function() {
    $("#recent").hide();
    $("#index").hide();
    $("#details").hide();
    $("#add").hide();
    $("#indexCells").empty();
    return $("#recentCells").empty();
  };

  hideAll();

  $(".nav li").click(function() {
    $(".nav").children().removeClass("active");
    return $(this).addClass("active");
  });

  $("#addlink").click(function() {
    $("#main").hide();
    $("body > .container").hide();
    return $("#add").show();
  });

  showDetails = function(data) {
    hideAll();
    $("#details").show();
    data = $.parseJSON(data);
    $("#updateForm").data("oldDetails", data[0]);
    $("#updateTitle").val(data[0].title);
    return $("#updateContent").val(data[0].body);
  };

  $("#recentlink").click(function() {
    return recentLinkAction();
  });

  recentLinkAction = function() {
    $("#main").hide();
    hideAll();
    $("#recent").show();
    $("#recentCells").empty();
    return $.post("./dostuff.php?action=recent", function(data) {
      return $.each($.parseJSON(data), function() {
        this.updated = this.updated === "0000-00-00 00:00:00" ? "never" : Date.parse(this.updated).toString('hh:mm:ss tt - MM.dd.yy');
        this.created = this.created === "0000-00-00 00:00:00" ? "never" : Date.parse(this.created).toString('hh:mm:ss tt - MM.dd.yy');
        $("#recentCells").append("<tr id=" + this.id + "><td>" + this.title + "</td><td>" + this.body.substring(0, 40) + "</td><td>" + this.created + "</td><td>" + this.updated + "</td></tr>");
        return $("#" + this.id).click(function() {
          return $.post("./dostuff.php?action=detail", {
            id: this.id
          }, function(data) {
            return showDetails(data);
          });
        });
      });
    });
  };

  $("#indexlink").click(function() {
    $("#main").hide();
    hideAll();
    $("#index").show();
    return $.post("./dostuff.php?action=index", {
      offset: 0
    }, function(data) {
      $("#indexCells").empty();
      return $.each($.parseJSON(data), function() {
        this.updated = this.updated === "0000-00-00 00:00:00" ? "never" : Date.parse(this.updated).toString('hh:mm:ss tt - MM.dd.yy');
        this.created = this.created === "0000-00-00 00:00:00" ? "never" : Date.parse(this.created).toString('hh:mm:ss tt - MM.dd.yy');
        $("#indexCells").append("<tr id=" + this.id + "><td class=\"indexTitle\">" + this.title + "</td><td>" + this.created + "</td><td>" + this.updated + "</td></tr>");
        return $("#" + this.id).click(function() {
          return $.post("./dostuff.php?action=detail", {
            id: this.id
          }, function(data) {
            return showDetails(data);
          });
        });
      });
    });
  });

  $("#setOffset > button").click(function() {
    var form, move, offset, url;
    event.preventDefault();
    form = $("#setOffset");
    offset = form.find(this).val();
    url = form.attr('action');
    move = $(this).attr("class");
    return $.post(url, {
      offset: offset
    }, function(data) {
      $("#indexCells").empty();
      $.each($.parseJSON(data), function() {
        this.updated = this.updated === "0000-00-00 00:00:00" ? "never" : Date.parse(this.updated).toString('hh:mm:ss tt - MM.dd.yy');
        this.created = this.created === "0000-00-00 00:00:00" ? "never" : Date.parse(this.created).toString('hh:mm:ss tt - MM.dd.yy');
        $("#indexCells").append("<tr id=" + this.id + "><td class=\"indexTitle\">" + this.title + "</td><td>" + this.created + "</td><td>" + this.updated + "</td></tr>");
        return $("#" + this.id).click(function() {
          return $.post("./dostuff.php?action=detail", {
            id: this.id
          }, function(data) {
            return showDetails(data);
          });
        });
      });
      if (move === "next" && offset >= data.length) {
        $("#prev, #next").val(offset + 10);
      }
      if (move === "prev" && offset <= 0) {
        return $("#prev, #next").val(offset - 10);
      }
    });
  });

  /*
  # Form Stuff
  */


  $("#addForm").submit(function() {
    var content, form, title, url;
    event.preventDefault();
    form = $(this);
    title = form.find('input[name="title"]').val();
    content = form.find('textarea[name="content"]').val();
    url = form.attr('action');
    return $.post(url, {
      title: title,
      content: content
    }, function(data) {
      $(':input', '#addForm').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
      return $("#addNote").fadeIn(400).delay(1200).fadeOut(400, function() {
        hideAll();
        return recentLinkAction();
      });
    });
  });

  $("#updateBtn").click(function() {
    var content, form, oldData, title, url;
    event.preventDefault();
    form = $("#updateForm");
    title = form.find('input[name="title"]').val();
    content = form.find('textarea[name="content"]').val();
    url = form.attr('action');
    oldData = $("#updateForm").data("oldDetails");
    return $.post(url, {
      title: title,
      content: content,
      id: oldData.id
    }, function(data) {
      $(':input', '#addForm').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
      return $("#updateNote").fadeIn(400).delay(1200).fadeOut(400, function() {
        hideAll();
        return recentLinkAction();
      });
    });
  });

  $("#deleteBtn").click(function() {
    var form, oldData;
    event.preventDefault();
    form = $("#updateForm");
    oldData = $("#updateForm").data("oldDetails");
    return $.post("./dostuff.php?action=delete", {
      id: oldData.id
    }, function(data) {
      $(':input', '#addForm').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
      hideAll();
      return recentLinkAction();
    });
  });

}).call(this);
