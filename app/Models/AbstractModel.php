<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

// This base model exists to automatically save a server-specific auto-incrementing id to the database, for each child type that inherits from this class
// --
abstract class AbstractModel extends Model
{
	// --------------------
	// - Parent Overrides -
	// --------------------

	/**
	 * Save the model to the database.
	 * --> Overriding the parent method to add the unique_id, unique to this server
	 *
	 * @param  array  $options
	 * @return bool
	 */
	public function save(array $options = [])
	{
		$this->mergeAttributesFromClassCasts();
	    $query = $this->newModelQuery();

	    // If the "saving" event returns false we'll bail out of the save and return
	    // false, indicating that the save failed. This provides a chance for any
	    // listeners to cancel save operations if validations fail or whatever.
	    if ($this->fireModelEvent('saving') === false) {
	        return false;
	    }

	    // If the model already exists in the database we can just update our record
	    // that is already in this database using the current IDs in this "where"
	    // clause to only update this model. Otherwise, we'll just insert them.
	    if ($this->exists) {
	        $saved = $this->isDirty() ?
	                    $this->performUpdate($query) : true;
	    }

	    // If the model is brand new, we'll insert it into our database and set the
	    // ID attribute on the model to the value of the newly inserted row's ID
	    // which is typically an auto-increment value managed by the database.
	    else {
	        $saved = $this->performInsert($query);

	        // -- Custom code --
	        $dirty = $this->getDirty();
	        $this->setKeysForSaveQuery($query)->update($dirty);
	        // -- End custom code --

	        if (! $this->getConnectionName() &&
	            $connection = $query->getConnection()) {
	            $this->setConnection($connection->getName());
	        }
	    }

	    // If the model is successfully saved, we need to do a few more things once
	    // that is done. We will call the "saved" method here to run any actions
	    // we need to happen after a model gets successfully saved right here.
	    if ($saved) {
	        $this->finishSave($options);
	    }

	    return $saved;
	}

	// -------------------
	// - Private Methods -
	// -------------------

	/**
	 * Insert the given attributes and set the ID on the model.
	 * --> Overriding the parent method to add the unique_id, unique to this server
	 * 
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  array  $attributes
	 * @return void
	 */
	protected function insertAndSetId(Builder $query, $attributes)
	{
		// Parent code, copied over
	    $id = $query->insertGetId($attributes, $keyName = $this->getKeyName());
	    $this->setAttribute($keyName, $id);

	    // Custom code
	    $unique_id = env('SERVER_NUMBER') . 's' . $id;
	    $this->custom_id = $unique_id;
	}
}