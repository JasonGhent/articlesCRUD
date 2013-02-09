hideAll = () ->
  $("#recent").hide()
  $("#index").hide()
  $("#details").hide()
  $("#add").hide()
  $("#indexCells").empty()
  $("#recentCells").empty()
hideAll()

$(".nav li").click ->
  $(".nav").children().removeClass("active")
  $(this).addClass("active")

$("#addlink").click ->
  $("#main").hide()
  $("body > .container").hide()
  $("#add").show()

showDetails = (data) ->
  hideAll()
  $("#details").show()
  data = $.parseJSON(data)
  $("#updateForm").data("oldDetails", data[0])
  $("#updateTitle").val(data[0].title)
  $("#updateContent").val(data[0].body)

$("#recentlink").click(->
  recentLinkAction()
)

recentLinkAction = () ->
  $("#main").hide()
  hideAll()
  $("#recent").show()
  $("#recentCells").empty()
  $.post("routes/dostuff.php?action=recent", (data) ->
    $.each $.parseJSON(data), ->
      @updated = if @updated is @created then "never" else Date.parse(@updated).toString('hh:mm:ss tt - MM.dd.yy')
#      @updated = if @updated == "0000-00-00 00:00:00" then "never" else Date.parse(@updated).toString('hh:mm:ss tt - MM.dd.yy')
      @created = if @created == "0000-00-00 00:00:00" then "never" else Date.parse(@created).toString('hh:mm:ss tt - MM.dd.yy')
      $("#recentCells").append("<tr id="+@id+"><td>"+@title+"</td><td>"+@body.substring(0,40)+"</td><td>"+@created+"</td><td>"+@updated+"</td></tr>")
      $("#"+@id).click(->
        $.post("routes/dostuff.php?action=detail", { id:@id}, (data) ->
          showDetails(data)
        )
      )
  )

$("#indexlink").click ->
  $("#main").hide()
  hideAll()
  $("#index").show()
  $.post("routes/dostuff.php?action=index", { offset:0 } ,(data) ->
    $("#indexCells").empty()
    $.each $.parseJSON(data), ->
#      @updated = if @updated is @created then "never" else Date.parse(@updated).toString('hh:mm:ss tt - MM.dd.yy')
      @updated = if @updated == "0000-00-00 00:00:00" then "never" else Date.parse(@updated).toString('hh:mm:ss tt - MM.dd.yy')
      @created = if @created == "0000-00-00 00:00:00" then "never" else Date.parse(@created).toString('hh:mm:ss tt - MM.dd.yy')
      $("#indexCells").append("<tr id="+@id+"><td class=\"indexTitle\">"+@title+"</td><td>"+@created+"</td><td>"+@updated+"</td></tr>")
      $("#"+@id).click(->
        $.post("routes/dostuff.php?action=detail", { id:@id}, (data) ->
          showDetails(data)
        )
      )
  )

$("#setOffset > button").click ->
  event.preventDefault()
  form = $("#setOffset")
  offset = form.find(this).val()
  url = form.attr('action')
  move = $(this).attr("class")
  $.post(url, { offset:offset } ,(data) ->
    $("#indexCells").empty()
    $.each $.parseJSON(data), ->
#      @updated = if @updated is @created then "never" else Date.parse(@updated).toString('hh:mm:ss tt - MM.dd.yy')
      @updated = if @updated == "0000-00-00 00:00:00" then "never" else Date.parse(@updated).toString('hh:mm:ss tt - MM.dd.yy')
      @created = if @created == "0000-00-00 00:00:00" then "never" else Date.parse(@created).toString('hh:mm:ss tt - MM.dd.yy')
      $("#indexCells").append("<tr id="+@id+"><td class=\"indexTitle\">"+@title+"</td><td>"+@created+"</td><td>"+@updated+"</td></tr>")
      $("#"+@id).click(->
        $.post("routes/dostuff.php?action=detail", { id:@id}, (data) ->
          showDetails(data)
        )
      )
    if move is "next" and offset >= data.length
      $("#prev, #next").val(offset+10)
    if move is "prev" and offset <= 0
      $("#prev, #next").val(offset-10)
  )

###
# Form Stuff
###

#add article
addArticle = (options) ->
  $.post(options.url, { title: options.title, content: options.content } ,options.success)

$("#addForm").submit ->
  event.preventDefault()
  form = $(this)
  addArticle(
    url: form.attr('action')
    title: form.find('input[name="title"]').val()
    content: form.find('textarea[name="content"]').val()
    success: (data) ->
      $(':input','#addForm')
        .not(':button, :submit, :reset, :hidden')
        .val('').removeAttr('checked').removeAttr('selected')
      $("#addNote").fadeIn(400).delay(1200).fadeOut(400, ->
        hideAll()
        recentLinkAction()
      )
  )


#update article
updateArticle = (options) ->
  $.post(options.url, { title: options.title, content: options.content, id: options.oldData.id }, options.success)

$("#updateBtn").click(->
  event.preventDefault()
  form = $("#updateForm")
  updateArticle(
    url: form.attr('action')
    title: form.find('input[name="title"]').val()
    content: form.find('textarea[name="content"]').val()
    oldData: form.data("oldDetails")
    success: (data) ->
      $(':input','#addForm')
        .not(':button, :submit, :reset, :hidden')
        .val('').removeAttr('checked').removeAttr('selected')
      $("#updateNote").fadeIn(400).delay(1200).fadeOut(400, ->
        hideAll()
        recentLinkAction()
      )
    )
)


#delete article
deleteArticle = (options) ->
  $.post("routes/dostuff.php?action=delete", { id: options.oldData.id }, options.success)

$("#deleteBtn").click(->
  event.preventDefault()
  form = $("#updateForm")
  deleteArticle(
    oldData: $(form).data("oldDetails")
    success: (data) ->
      $(':input','#addForm')
        .not(':button, :submit, :reset, :hidden')
        .val('').removeAttr('checked').removeAttr('selected')
      hideAll()
      recentLinkAction()
  )
)
