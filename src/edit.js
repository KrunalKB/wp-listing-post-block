import { __ } from "@wordpress/i18n";
import { RawHTML } from "@wordpress/element";
import { useBlockProps, InspectorControls } from "@wordpress/block-editor";
import { PanelBody, ToggleControl, QueryControls } from "@wordpress/components";
//eslint-disable-next-line @wordpress/no-unsafe-wp-apis
import { format, dateI18n, __experimentalGetSettings } from "@wordpress/date";
import { useSelect } from "@wordpress/data";
import "./editor.scss";

export default function Edit({ attributes, setAttributes }) {
	const { numberOfPosts, displayFeaturedImage } = attributes;

	const onDisplayFeaturedImageChange = (value) => {
		setAttributes({ displayFeaturedImage: value });
	};

	const onNumberOfItemsChange = (value) => {
		setAttributes({ numberOfPosts: value });
	};

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
		<>
			<InspectorControls>
				<PanelBody>
					<ToggleControl
						label={__("Display Featured Image", "latest-posts")}
						checked={displayFeaturedImage}
						onChange={onDisplayFeaturedImageChange}
					/>
					<QueryControls
						numberOfItems={numberOfPosts}
						onNumberOfItemsChange={onNumberOfItemsChange}
					/>
				</PanelBody>
			</InspectorControls>
			<ul {...useBlockProps()}>
				{posts &&
					posts.map((post) => {
						const featuredImage =
							post._embedded &&
							post._embedded["wp:featuredmedia"] &&
							post._embedded["wp:featuredmedia"].length > 0 &&
							post._embedded["wp:featuredmedia"][0];

						return (
							<li key={post.id}>
								{displayFeaturedImage && featuredImage && (
									<img
										src={featuredImage.media_details.sizes.medium.source_url}
										alt={featuredImage.alt_text}
									/>
								)}

								<h5>
									<a href={post.link}>
										{post.title.rendered ? (
											<RawHTML>{post.title.rendered}</RawHTML>
										) : (
											__("(No title)", "latest-posts")
										)}
									</a>
								</h5>

								{post.date_gmt && (
									<time dateTime={format("c", post.date_gmt)}>
										{dateI18n(
											__experimentalGetSettings().formats.date,
											post.date_gmt
										)}
									</time>
								)}

								{post.excerpt.rendered && (
									<RawHTML>{post.excerpt.rendered}</RawHTML>
								)}
							</li>
						);
					})}
			</ul>
		</>
	);
}
