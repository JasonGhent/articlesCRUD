
/*
# Display
*/


(function() {
  var Article, LinkAction, LinkActionView, NewArticleView, article, hideAll, linkAction, showDetails;

  hideAll = function() {
    $("#recent").hide();
    $("#index").hide();
    $("#details").hide();
    $("#add").hide();
    $("#indexCells").empty();
    return $("#recentCells").empty();
  };

  showDetails = function(data) {
    hideAll();
    $("#details").show();
    $("#updateForm, #deleteForm").data("oldDetails", data[0]);
    $("#updateTitle").val(data[0].title);
    return $("#updateContent").val(data[0].body);
  };

  $(".nav li").click(function() {
    $(".nav").children().removeClass("active");
    return $(this).addClass("active");
  });

  /*
  # Pagination
  */


  $("#setOffset > button").click(function() {
    var direction, form, increment, move, offset, url;
    event.preventDefault();
    form = $("#setOffset");
    increment = 10;
    offset = parseInt(form.find(this).val());
    url = form.attr('action');
    direction = $(this).attr("id");
    move = function(direction, data) {
      $("#indexCells").empty();
      if (direction === "next") {
        $('#prev').val($('#prev').val() * 1 + increment);
        $('#next').val($('#next').val() * 1 + increment);
      } else if (direction === "prev") {
        $('#prev').val($('#prev').val() * 1 - increment);
        $('#next').val($('#next').val() * 1 - increment);
      }
      return $.each(data, function() {
        this.updated = this.updated === "0000-00-00 00:00:00" ? "never" : Date.parse(this.updated).toString('hh:mm:ss tt - MM.dd.yy');
        this.created = this.created === "0000-00-00 00:00:00" ? "never" : Date.parse(this.created).toString('hh:mm:ss tt - MM.dd.yy');
        $("#indexCells").append("<tr id=" + this.id + "><td class=\"indexTitle\">" + this.title + "</td><td>" + this.created + "</td><td>" + this.updated + "</td></tr>");
        return $("#" + this.id).click(function() {
          return $.post("routes/dostuff.php?action=detail", {
            id: this.id
          }, function(data) {
            return showDetails(data);
          });
        });
      });
    };
    return $.post(url, {
      offset: offset
    }, function(data) {
      if (offset >= 0 && _.size(data) > 0) {
        return move(direction, data);
      }
    });
  });

  /*
  # Form Stuff
  */


  Article = function() {};

  Article.prototype.add = function(options) {
    return $.post(options.url, {
      title: options.title,
      content: options.content
    }, options.success);
  };

  Article.prototype.update = function(options) {
    return $.post(options.url, {
      title: options.title,
      content: options.content,
      id: options.oldData.id
    }, options.success);
  };

  Article.prototype["delete"] = function(options) {
    return $.post(options.url, {
      id: options.oldData.id
    }, options.success);
  };

  NewArticleView = function(options) {
    var article;
    article = options.article;
    $("#addForm").submit(function() {
      var form;
      event.preventDefault();
      form = $(this);
      return article.add({
        url: form.attr('action'),
        title: form.find('input[name="title"]').val(),
        content: form.find('textarea[name="content"]').val(),
        success: function(data) {
          $(':input', '#addForm').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
          return $("#addNote").fadeIn(400).delay(1200).fadeOut(400, function() {
            hideAll();
            return recentLinkAction();
          });
        }
      });
    });
    $("#updateForm").submit(function() {
      var form;
      event.preventDefault();
      form = $(this);
      return article.update({
        url: form.attr('action'),
        title: form.find('input[name="title"]').val(),
        content: form.find('textarea[name="content"]').val(),
        oldData: form.data("oldDetails"),
        success: function(data) {
          $(':input', '#addForm').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
          return $("#updateNote").fadeIn(400).delay(1200).fadeOut(400, function() {
            hideAll();
            return indexLinkAction();
          });
        }
      });
    });
    return $("#deleteForm").submit(function() {
      var form;
      event.preventDefault();
      form = $(this);
      return article["delete"]({
        url: form.attr('action'),
        oldData: form.data("oldDetails"),
        success: function(data) {
          $(':input', '#addForm').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
          hideAll();
          return recentLinkAction();
        }
      });
    });
  };

  /*
  # Link Actions
  */


  LinkAction = function() {};

  LinkAction.prototype.add = function(options) {
    $("#main").hide();
    $("body > .container").hide();
    return $("#add").show();
  };

  LinkAction.prototype.recent = function(options) {
    hideAll();
    $("#main").hide();
    $("#recent").show();
    $("#recentCells").empty();
    $('#prev').val(0);
    $('#next').val(10);
    return $.post("routes/dostuff.php?action=recent", function(data) {
      return $.each(data, function() {
        this.updated = this.updated === this.created ? "never" : Date.parse(this.updated).toString('hh:mm:ss tt - MM.dd.yy');
        this.created = this.created === "0000-00-00 00:00:00" ? "never" : Date.parse(this.created).toString('hh:mm:ss tt - MM.dd.yy');
        $("#recentCells").append("<tr id=" + this.id + "><td>" + this.title + "</td><td>" + this.body.substring(0, 40) + "</td><td>" + this.created + "</td><td>" + this.updated + "</td></tr>");
        return $("#" + this.id).click(function() {
          return $.post("routes/dostuff.php?action=detail", {
            id: this.id
          }, function(data) {
            return showDetails(data);
          });
        });
      });
    });
  };

  LinkAction.prototype.index = function(options) {
    hideAll();
    $("#main").hide();
    $("#index").show();
    return $.post("routes/dostuff.php?action=index", {
      offset: 0
    }, function(data) {
      $("#indexCells").empty();
      return $.each(data, function() {
        this.updated = this.updated === "0000-00-00 00:00:00" ? "never" : Date.parse(this.updated).toString('hh:mm:ss tt - MM.dd.yy');
        this.created = this.created === "0000-00-00 00:00:00" ? "never" : Date.parse(this.created).toString('hh:mm:ss tt - MM.dd.yy');
        $("#indexCells").append("<tr id=" + this.id + "><td class=\"indexTitle\">" + this.title + "</td><td>" + this.created + "</td><td>" + this.updated + "</td></tr>");
        return $("#" + this.id).click(function() {
          return $.post("routes/dostuff.php?action=detail", {
            id: this.id
          }, function(data) {
            return showDetails(data);
          });
        });
      });
    });
  };

  LinkActionView = function(options) {
    var linkAction;
    linkAction = options.linkAction;
    $("#addlink").click(function() {
      return linkAction.add();
    });
    $("#indexlink").click(function() {
      return linkAction.index();
    });
    return $("#recentlink").click(function() {
      return linkAction.recent();
    });
  };

  /*
  # on DOM ready
  */


  article = new Article();

  new NewArticleView({
    article: article
  });

  linkAction = new LinkAction();

  new LinkActionView({
    linkAction: linkAction
  });

}).call(this);
