module.exports = {
    content: ['../../../layouts/**/*.html', '../layouts/**/*.html','../static/**/*.js'],
    defaultExtractor: content => content.match(/[A-Za-z0-9-_:/]+/g) || []
}