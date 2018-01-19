import { getLatestPosts } from './data.js';

const { __ } = wp.i18n;
const { Component } = wp.element;

export default class PostList extends Component {
  constructor( props ) {
    super( ...arguments );
    this.state = {
      posts: [ { title: { rendered: __( 'Loading posts..' ) } } ],
    };

    getLatestPosts()
      .then( posts => {
        return this.setState( { posts } );
      } );
  }

  render() {
    return (
      <ul className={ this.props.className }>
        { this.state.posts.map( post => {
          return <li>{ post.title.rendered }</li>
        })}
      </ul>
    );
  }
}
