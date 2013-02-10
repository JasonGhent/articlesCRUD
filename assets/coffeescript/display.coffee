###
# Display
###
hideAll = () ->
  $("#recent").hide()
  $("#index").hide()
  $("#details").hide()
  $("#add").hide()
  $("#indexCells").empty()
  $("#recentCells").empty()

showDetails = (data) ->
  hideAll()
  $("#details").show()
  $("#updateForm, #deleteForm").data("oldDetails", data[0])
  $("#updateTitle").val(data[0].title)
  $("#updateContent").val(data[0].body)

$(".nav li").click ->
  $(".nav").children().removeClass("active")
  $(this).addClass("active")



###
# Pagination
###
$("#setOffset > button").click ->
  event.preventDefault()
  form = $("#setOffset")
  increment = 10
  offset = parseInt(form.find(this).val())
  url = form.attr('action')
  direction = $(this).attr("id")

  move = (direction, data) ->
    $("#indexCells").empty()
    if direction is "next"
       $('#prev').val($('#prev').val()*1+increment)
       $('#next').val($('#next').val()*1+increment)
    else if direction is "prev"
      $('#prev').val($('#prev').val()*1-increment)
      $('#next').val($('#next').val()*1-increment)
    $.each (data), ->
      @updated = if @updated is "0000-00-00 00:00:00" then "never" else Date.parse(@updated).toString('hh:mm:ss tt - MM.dd.yy')
      @created = if @created is "0000-00-00 00:00:00" then "never" else Date.parse(@created).toString('hh:mm:ss tt - MM.dd.yy')
      $("#indexCells").append("<tr id="+@id+"><td class=\"indexTitle\">"+@title+"</td><td>"+@created+"</td><td>"+@updated+"</td></tr>")
      $("#"+@id).click ->
        $.post "routes/dostuff.php?action=detail", { id:@id}, (data) ->
          showDetails(data)
    
  $.post url, { offset:offset } ,(data) ->
    if offset >= 0 and _.size(data) > 0
      move(direction, data)



###
# Form Stuff
###

# Article object definitions
Article = () ->

Article.prototype.add = (options) ->
  $.post(options.url, { title: options.title, content: options.content } ,options.success)
Article.prototype.update = (options) ->
  $.post(options.url, { title: options.title, content: options.content, id: options.oldData.id }, options.success)
Article.prototype.delete = (options) ->
  $.post(options.url, { id: options.oldData.id }, options.success)

#Article View Defined
NewArticleView = (options) ->
  article = options.article

  $("#addForm").submit ->
    event.preventDefault()
    form = $(@)
    article.add
      url: form.attr('action')
      title: form.find('input[name="title"]').val()
      content: form.find('textarea[name="content"]').val()
      success: (data) ->
        $(':input','#addForm')
          .not(':button, :submit, :reset, :hidden')
          .val('').removeAttr('checked').removeAttr('selected')
        $("#addNote").fadeIn(400).delay(1200).fadeOut 400, ->
          hideAll()
          recentLinkAction()
    
  $("#updateForm").submit ->
    event.preventDefault()
    form = $(@)
    article.update
      url: form.attr('action')
      title: form.find('input[name="title"]').val()
      content: form.find('textarea[name="content"]').val()
      oldData: form.data("oldDetails")
      success: (data) ->
        $(':input','#addForm')
          .not(':button, :submit, :reset, :hidden')
          .val('').removeAttr('checked').removeAttr('selected')
        $("#updateNote").fadeIn(400).delay(1200).fadeOut 400, ->
          hideAll()
          indexLinkAction()
  
  $("#deleteForm").submit ->
    event.preventDefault()
    form = $(@)
    article.delete
      url: form.attr('action')
      oldData: form.data("oldDetails")
      success: (data) ->
        $(':input','#addForm')
          .not(':button, :submit, :reset, :hidden')
          .val('').removeAttr('checked').removeAttr('selected')
        hideAll()
        recentLinkAction()



###
# Link Actions
###
LinkAction = () ->
LinkAction.prototype.add = (options) ->
  $("#main").hide()
  $("body > .container").hide()
  $("#add").show()
LinkAction.prototype.recent = (options) ->
  hideAll()
  $("#main").hide()
  $("#recent").show()
  $("#recentCells").empty()
  $('#prev').val(0)
  $('#next').val(10)
  $.post "routes/dostuff.php?action=recent", (data) ->
    $.each (data), ->
      @updated = if @updated is @created then "never" else Date.parse(@updated).toString('hh:mm:ss tt - MM.dd.yy')
      @created = if @created == "0000-00-00 00:00:00" then "never" else Date.parse(@created).toString('hh:mm:ss tt - MM.dd.yy')
      $("#recentCells").append("<tr id="+@id+"><td>"+@title+"</td><td>"+@body.substring(0,40)+"</td><td>"+@created+"</td><td>"+@updated+"</td></tr>")
      $("#"+@id).click ->
        $.post "routes/dostuff.php?action=detail", { id:@id}, (data) ->
          showDetails(data)
LinkAction.prototype.index = (options) ->
  hideAll()
  $("#main").hide()
  $("#index").show()
  $.post "routes/dostuff.php?action=index", { offset:0 } ,(data) ->
    $("#indexCells").empty()
    $.each (data), ->
      @updated = if @updated == "0000-00-00 00:00:00" then "never" else Date.parse(@updated).toString('hh:mm:ss tt - MM.dd.yy')
      @created = if @created == "0000-00-00 00:00:00" then "never" else Date.parse(@created).toString('hh:mm:ss tt - MM.dd.yy')
      $("#indexCells").append("<tr id="+@id+"><td class=\"indexTitle\">"+@title+"</td><td>"+@created+"</td><td>"+@updated+"</td></tr>")
      $("#"+@id).click ->
        $.post "routes/dostuff.php?action=detail", { id:@id}, (data) ->
          showDetails(data)

LinkActionView = (options) ->
  linkAction = options.linkAction
  $("#addlink").click ->
    linkAction.add()
  $("#indexlink").click ->
    linkAction.index()
  $("#recentlink").click ->
    linkAction.recent()
  
###
# on DOM ready
###

article = new Article()
new NewArticleView({ article: article })

linkAction = new LinkAction()
new LinkActionView({ linkAction: linkAction })

