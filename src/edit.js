import { __ } from "@wordpress/i18n";
import { RawHTML } from "@wordpress/element";
import { useBlockProps } from "@wordpress/block-editor";
import { useSelect } from "@wordpress/data";
import "./editor.scss";

export default function Edit({ attributes }) {
	const { numberOfPosts } = attributes;
	const posts = useSelect(
		(select) => {
			return select("core").getEntityRecords("postType", "post", {
				per_page: numberOfPosts,
				_embed: true,
			});
		},
		[numberOfPosts]
	);
	console.log(posts);
	return (
		<ul {...useBlockProps()}>
			{posts &&
				posts.map((post) => {
					return (
						<li key={post.id}>
							<h5>
								<a href={post.link}>
									{post.title.rendered ? (
										<RawHTML>{post.title.rendered}</RawHTML>
									) : (
										__("(No title)", "latest-posts")
									)}
								</a>
							</h5>
							{post.date_gmp && <time dateTime=""></time>}
						</li>
					);
				})}
		</ul>
	);
}
