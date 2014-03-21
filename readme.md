#API

##POSTS
api/posts - all posts
api/posts/{id} - post with id {id}
api/posts?options - filter posts based on options, check bellow for available options


##CATEGORIES
api/categories - all categories
api/categories/{id} - category with id {id}
api/categories?options - filter categories based on options, check bellow for available options

##POLLS
api/polls - all polls
api/polls/{id} - poll with id {id}
api/polls?options - filter polls based on options, check bellow for available options

##BANNERS
api/banners - all banners
api/banners/{id} - banner with id {id}
api/banners?options - filter banners based on options, check bellow for available options

##ALBUMS
api/albums - all albums
api/albums/{id} - album with id {id}
api/albums?options - filter albums based on options, check bellow for available options

##USERS
api/user - get details of logged in user
(POST) api/user/login - logs in a user; input parameters: username or email, password, remember - bool (optional)
(POST) api/user - creates a new user; input parameters: username, email, first_name, last_name, password, password_confirmation
(PUT) api/user - updates user info; input parameters: username, email, first_name, last_name, password

##OPTIONS
types={array of type slugs (about,news,events,activities,summer-camp,vlog) or ids} - available for posts, categories
positions={array of position slugs (slider…) or ids} - available for banners
categories={array of category ids} - available for posts
search={search query} - filter posts (each parameter is optional) - available for posts
language={(en, bg)} - posts, categories - available for posts, categories, polls
order={(created_at-default, order, title, type_id, category_id, id)} - available for  posts, categories, polls
sort={(asc, desc)} - available for posts, categories, polls
limit={limit (10-default)} - available for posts, categories, polls
offset={offset (0-default)} - available for posts, categories, polls
